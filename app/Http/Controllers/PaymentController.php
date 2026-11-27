<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = (bool) config('midtrans.is_3ds');
    }

    /**
     * Buat payment dan dapatkan Snap token
     */
    public function create(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to order');
        }

        if ($order->status === 'cancelled') {
            return redirect()->route('catalog.index')->with('error', 'Pesanan ini sudah dibatalkan.');
        }

        if ($order->payment && ($order->isPaid() || $order->payment->isPending())) {
            return redirect()->route('payment.show', $order->payment);
        }

        try {
            $transactionId = 'TRX-' . $order->order_number . '-' . time();

            $itemDetails = [];
            foreach ($order->items as $item) {
                $itemDetails[] = [
                    'id' => (string) ($item->product_id ?? 'CUSTOM-' . $item->id),
                    'price' => (int) $item->price,
                    'quantity' => $item->quantity,
                    'name' => Str::limit($item->product_name, 50, ''),
                ];
            }

            if ($order->shipping_cost > 0) {
                $itemDetails[] = [
                    'id' => 'SHIPPING',
                    'price' => (int) $order->shipping_cost,
                    'quantity' => 1,
                    'name' => 'Biaya Pengiriman',
                ];
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $transactionId,
                    'gross_amount' => (int) $order->total_amount,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => Str::limit($order->customer_name, 100, ''),
                    'email' => $order->customer_email,
                    'phone' => Str::limit((string) $order->customer_phone, 19, ''),
                    'shipping_address' => [
                        'address' => Str::limit((string) $order->shipping_address, 200, ''),
                    ],
                ],
                'enabled_payments' => [
                    'qris',
                    'bca_va',
                    'bni_va',
                    'bri_va',
                    'permata_va',
                    'echannel',   // Mandiri Bill
                    'other_va',
                ],
                'expiry' => ['unit' => 'hours', 'duration' => 24],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $payment = Payment::create([
                'order_id' => $order->id,
                'transaction_id' => $transactionId,
                'payment_type' => 'pending',
                'amount' => $order->total_amount,
                'status' => 'pending',
                'snap_token' => $snapToken,
                'expired_at' => now()->addHours(24),
            ]);

            return redirect()->route('payment.show', $payment);

        } catch (\Exception $e) {
            Log::error('Midtrans payment creation failed: ' . $e->getMessage());

            return back()->with('error', 'Gagal membuat pembayaran. ' . (config('app.debug') ? $e->getMessage() : 'Silakan coba lagi.'));
        }
    }

    /**
     * Tampilkan halaman payment
     */
    public function show(Payment $payment)
    {
        $order = $payment->order;

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('payment.show', compact('payment', 'order'));
    }

    /**
     * Webhook dari Midtrans
     */
    public function notification(Request $request)
    {
        try {
            $payment = Payment::where('transaction_id', $request->input('order_id'))->firstOrFail();
            $this->syncFromMidtrans($payment);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Midtrans notification handler failed: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Polling dari frontend (sekaligus fallback bila webhook belum masuk)
     */
    public function checkStatus(Payment $payment)
    {
        $order = $payment->order;

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($payment->isPending()) {
            try {
                $this->syncFromMidtrans($payment);
                $payment->refresh();
                $order->refresh();
            } catch (\Exception $e) {
                Log::warning('Midtrans status sync skipped: ' . $e->getMessage());
            }
        }

        return response()->json([
            'status' => $payment->status,
            'order_status' => $order->status,
            'paid_at' => $payment->paid_at?->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Ambil status terbaru langsung dari Midtrans lalu perbarui database.
     */
    private function syncFromMidtrans(Payment $payment): void
    {
        // Jangan menurunkan status yang sudah final
        if ($payment->status === 'settlement') {
            return;
        }

        $data = \Midtrans\Transaction::status($payment->transaction_id);
        $data = is_array($data) ? (object) $data : $data;

        $status = $data->transaction_status;
        $fraud = $data->fraud_status ?? null;
        $order = $payment->order;

        $payment->payment_type = $data->payment_type ?? $payment->payment_type;

        if ($status === 'settlement' || ($status === 'capture' && $fraud === 'accept')) {
            $payment->status = 'settlement';
            $payment->paid_at = now();
        } elseif ($status === 'pending') {
            if (!empty($data->va_numbers)) {
                $payment->va_number = $data->va_numbers[0]->va_number ?? null;
                $payment->bank_name = $data->va_numbers[0]->bank ?? null;
            }
        } elseif (in_array($status, ['deny', 'cancel', 'expire'], true)) {
            $payment->status = $status;

            if ($order->status !== 'cancelled') {
                $order->status = 'cancelled';
                $this->restoreStock($order);
            }
        }

        $payment->midtrans_response = json_encode($data);
        $payment->save();
        $order->save();
    }

    private function restoreStock(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_id) {
                Product::where('id', $item->product_id)->increment('stock', $item->quantity);
            }
        }
    }
}