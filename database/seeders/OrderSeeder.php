<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Outlet;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Pesanan contoh (produk yang sudah terjual) untuk halaman:
 *   - admin/sales      : daftar & detail pesanan
 *   - admin/analytics  : pendapatan 14 hari terakhir, tipe pesanan, status,
 *                        produk terlaris, pelanggan terbaik
 *   - admin/crm        : total belanja + pesanan terakhir per pelanggan
 *   - admin/dashboard  : pesanan terbaru, pesanan hari ini
 *
 * Aturan bisnisnya disamakan dengan CheckoutController supaya angkanya masuk akal:
 * ongkir per jarak (<=5 km 15rb, <=10 km 25rb, <=15 km 35rb), estimasi siap
 * = base per tipe (dine_in 15, take_away 20, delivery 30) + 2 menit per item,
 * dan status pembayaran Midtrans (settlement/pending/expire/cancel/deny).
 */
class OrderSeeder extends Seeder
{
    /**
     * Penanda data contoh, sekaligus pengecekan idempotensi: kalau transaksi
     * dengan prefix ini sudah ada, seeder tidak menambah data lagi.
     */
    private const TRX_PREFIX = 'TRX-DEMO-';

    private const ORDERS_PER_DAY = 4;
    private const DAYS_BACK = 14;
    private const OLDER_ORDERS = 4;

    /**
     * @var array<string, int> menit dasar per tipe pesanan (lihat CheckoutController)
     */
    private const READY_BASE_MINUTES = ['dine_in' => 15, 'take_away' => 20, 'delivery' => 30];

    /**
     * @var array<int, string>
     */
    private const PAYMENT_TYPES = ['qris', 'bank_transfer', 'gopay', 'shopeepay', 'credit_card'];

    /**
     * @var array<int, string|null>
     */
    private const NOTES = [
        null,
        'Jangan terlalu manis',
        null,
        'Bungkus terpisah ya',
        'Tanpa kacang',
        null,
        'Kirim sore hari',
        'Untuk hadiah, tolong beri pita',
    ];

    /**
     * @var array<int, string> nama jalan contoh untuk pesanan pengiriman
     */
    private const STREETS = [
        'Jl. Kebon Jeruk Raya', 'Jl. Panglima Polim V', 'Jl. Cipete Raya', 'Jl. Kemang Selatan',
        'Jl. Tebet Barat Dalam', 'Jl. Dago Asri', 'Jl. Riau', 'Jl. Manyar Jaya',
        'Jl. Darmo Permai', 'Jl. Wolter Monginsidi', 'Jl. Gatot Subroto', 'Jl. Ahmad Yani',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Payment::where('transaction_id', 'like', self::TRX_PREFIX.'%')->exists()) {
            $this->command?->info('Pesanan contoh sudah ada, OrderSeeder dilewati.');
            return;
        }

        $customers = User::where('role', 'user')->orderBy('id')->get();
        $products = Product::where('status', 'active')->orderBy('id')->get();
        $outlets = Outlet::with('tables')->get();

        if ($customers->count() < 2 || $products->count() < 2 || $outlets->isEmpty()) {
            $this->command?->warn('OrderSeeder dilewati: jalankan UserSeeder, OutletSeeder, ProductSeeder, dan CrmSeeder dulu.');
            return;
        }

        // RNG disemai supaya hasil seed konsisten saat diulang di DB lain.
        mt_srand(20260214);

        $typePool = array_merge(
            array_fill(0, 24, 'delivery'),
            array_fill(0, 22, 'take_away'),
            array_fill(0, 14, 'dine_in')
        );

        $statusPool = array_merge(
            array_fill(0, 34, 'completed'),
            array_fill(0, 9, 'pending'),
            array_fill(0, 7, 'processing'),
            array_fill(0, 5, 'ready'),
            array_fill(0, 5, 'cancelled')
        );

        shuffle($typePool);
        shuffle($statusPool);

        $timestamps = $this->orderTimestamps();
        $created = 0;
        $settled = 0.0;

        DB::transaction(function () use ($timestamps, $typePool, $statusPool, $customers, $products, $outlets, &$created, &$settled) {
            foreach ($timestamps as $index => $createdAt) {
                $seq = $index + 1;
                $customer = $this->pickCustomer($customers);
                $orderType = $typePool[$index % count($typePool)];
                $status = $statusPool[$index % count($statusPool)];

                // ---------- item pesanan ----------
                $itemCount = mt_rand(1, min(4, $products->count()));
                $picked = $itemCount === $products->count() ? $products : $products->random($itemCount);

                $items = [];
                $subtotal = 0.0;
                $totalQty = 0;

                foreach ($picked as $product) {
                    // produk box selalu 1 pcs per baris, mochi satuan 1-3 pcs
                    $isBox = str_starts_with((string) $product->sku, 'BOX');
                    $quantity = $isBox ? 1 : mt_rand(1, 3);
                    $lineSubtotal = round((float) $product->price * $quantity, 2);

                    $items[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'price' => $product->price,
                        'quantity' => $quantity,
                        'subtotal' => $lineSubtotal,
                    ];

                    $subtotal += $lineSubtotal;
                    $totalQty += $quantity;
                }

                // ---------- outlet / meja / alamat / ongkir ----------
                $outletId = null;
                $outletTableId = null;
                $shippingAddress = null;
                $deliveryDistance = null;
                $shippingCost = 0.0;

                if ($orderType === 'dine_in') {
                    $outlet = $outlets->random();
                    $outletId = $outlet->id;
                    $outletTableId = $outlet->tables->isNotEmpty() ? $outlet->tables->random()->id : null;
                } elseif ($orderType === 'take_away') {
                    $outletId = $outlets->random()->id;
                } else {
                    $outlet = $outlets->random();
                    $outletId = $outlet->id;
                    $deliveryDistance = mt_rand(10, 145) / 10; // 1.0 - 14.5 km
                    $shippingCost = $this->shippingCost($deliveryDistance);
                    $shippingAddress = $this->demoAddress($customer, $seq);
                }

                $totalAmount = round($subtotal + $shippingCost, 2);
                $readyAt = $createdAt->copy()->addMinutes(
                    (self::READY_BASE_MINUTES[$orderType] ?? 20) + ($totalQty * 2)
                );

                // ---------- pesanan ----------
                $order = new Order([
                    'order_number' => 'INV-'.$createdAt->format('Ymd').'-'.str_pad((string) $seq, 3, '0', STR_PAD_LEFT),
                    'order_type' => $orderType,
                    'outlet_id' => $outletId,
                    'outlet_table_id' => $outletTableId,
                    'user_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'customer_email' => $customer->email,
                    'customer_phone' => $this->demoPhone($customer->id),
                    'shipping_address' => $shippingAddress,
                    'subtotal' => $subtotal,
                    'shipping_cost' => $shippingCost,
                    'delivery_distance' => $deliveryDistance,
                    'estimated_ready_time' => $readyAt,
                    'total_amount' => $totalAmount,
                    'status' => $status,
                    'notes' => self::NOTES[$index % count(self::NOTES)],
                    'discount_amount' => 0,
                ]);
                $order->created_at = $createdAt;
                $order->updated_at = $createdAt;
                $order->save();

                foreach ($items as $itemData) {
                    $item = new OrderItem($itemData);
                    $item->order_id = $order->id;
                    $item->created_at = $createdAt;
                    $item->updated_at = $createdAt;
                    $item->save();
                }

                // ---------- pembayaran ----------
                $paymentStatus = $this->paymentStatusFor($status, $createdAt);
                $paymentType = self::PAYMENT_TYPES[$index % count(self::PAYMENT_TYPES)];
                $transactionId = self::TRX_PREFIX.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);

                $payment = new Payment([
                    'order_id' => $order->id,
                    'payment_method' => 'midtrans',
                    'transaction_id' => $transactionId,
                    'payment_type' => $paymentStatus === 'pending' ? 'pending' : $paymentType,
                    'amount' => $totalAmount,
                    'status' => $paymentStatus,
                    'paid_at' => $paymentStatus === 'settlement' ? $createdAt->copy()->addMinutes(mt_rand(1, 25)) : null,
                    'payment_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/demo-'.$order->order_number,
                    'midtrans_response' => json_encode([
                        'transaction_id' => $transactionId,
                        'order_id' => $order->order_number,
                        'transaction_status' => $paymentStatus,
                        'payment_type' => $paymentType,
                        'gross_amount' => number_format($totalAmount, 2, '.', ''),
                        'status_message' => 'midtrans payment notification (data contoh)',
                    ]),
                ]);
                $payment->created_at = $createdAt;
                $payment->updated_at = $createdAt;
                $payment->save();

                $created++;
                if ($paymentStatus === 'settlement') {
                    $settled += $totalAmount;
                }
            }
        });

        $byStatus = Order::selectRaw('status, COUNT(*) as jumlah')->groupBy('status')->pluck('jumlah', 'status');

        $this->command?->info("Pesanan contoh dibuat: {$created} pesanan (".$byStatus->toJson().'), lunas Rp '
            .number_format($settled, 0, ',', '.').'.');
    }

    /**
     * Sebaran waktu pesanan: 14 hari terakhir (4 pesanan/hari supaya grafik
     * analytics terisi) + beberapa pesanan lama untuk variasi riwayat pelanggan.
     *
     * @return array<int, Carbon>
     */
    private function orderTimestamps(): array
    {
        $slots = [];

        // Sisakan minimal 2 pesanan yang pasti jatuh di tanggal hari ini, supaya
        // kartu "pesanan hari ini" di dashboard tidak pernah kosong. Sisa slot
        // hari ini boleh mundur melewati tengah malam agar jamnya bervariasi.
        $minutesToday = max(5, min(240, (int) now()->diffInMinutes(now()->startOfDay()) - 2));

        for ($dayOffset = self::DAYS_BACK - 1; $dayOffset >= 0; $dayOffset--) {
            for ($i = 0; $i < self::ORDERS_PER_DAY; $i++) {
                if ($dayOffset !== 0) {
                    $slots[] = now()->subDays($dayOffset)->setTime(mt_rand(9, 21), mt_rand(0, 59), mt_rand(0, 59));

                    continue;
                }

                $slots[] = $i < 2
                    ? now()->subMinutes(mt_rand(2, $minutesToday))
                    : now()->subMinutes(mt_rand(2, 480));
            }
        }

        for ($i = 0; $i < self::OLDER_ORDERS; $i++) {
            $slots[] = now()->subDays(mt_rand(17, 45))->setTime(mt_rand(10, 20), mt_rand(0, 59), 0);
        }

        usort($slots, fn (Carbon $a, Carbon $b) => $a->timestamp <=> $b->timestamp);

        return $slots;
    }

    /**
     * Pelanggan dengan distribusi miring: pelanggan awal lebih sering memesan,
     * sehingga muncul pelanggan repeat di halaman CRM.
     */
    private function pickCustomer($customers): User
    {
        $skewed = (int) floor(pow(mt_rand(0, 1000) / 1000, 2) * $customers->count());

        return $customers[min($skewed, $customers->count() - 1)];
    }

    /**
     * Status pembayaran mengikuti status pesanan (lihat PaymentController).
     */
    private function paymentStatusFor(string $orderStatus, Carbon $createdAt): string
    {
        return match ($orderStatus) {
            'completed', 'ready' => 'settlement',
            'processing' => mt_rand(1, 10) <= 7 ? 'settlement' : 'pending',
            'pending' => $createdAt->lt(now()->subDay()) ? 'expire' : 'pending',
            default => ['cancel', 'deny', 'expire'][mt_rand(0, 2)],
        };
    }

    /**
     * Ongkir per jarak, sama dengan aturan CheckoutController.
     */
    private function shippingCost(float $distance): float
    {
        if ($distance <= 5) {
            return 15000.0;
        }

        if ($distance <= 10) {
            return 25000.0;
        }

        return 35000.0;
    }

    private function demoAddress(User $customer, int $seq): string
    {
        $street = self::STREETS[$seq % count(self::STREETS)];

        return 'Jl. '.substr($street, 4).' No. '.(($seq * 7) % 90 + 1)
            .', RT '.(($seq * 3) % 12 + 1).'/RW '.(($seq * 5) % 15 + 1)
            .', Jakarta';
    }

    private function demoPhone(int $userId): string
    {
        return '08'.str_pad((string) (11 + ($userId % 8)), 2, '0', STR_PAD_LEFT)
            .str_pad((string) (($userId * 7919) % 10000000), 7, '0', STR_PAD_LEFT);
    }
}
