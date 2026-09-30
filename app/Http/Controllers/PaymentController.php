<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Buat payment dan dapatkan Snap token
     */
    public function create(Request $request, Order $order)
    {
        // Validasi order milik user yang login
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to order');
        }

        // Cek apakah sudah ada payment pending
        if ($order->payment && $order->payment->isPending()) {
            return redirect()->route('payment.show', $order->payment);
        }

        try {
            $transactionId = 'TRX-' . $order->order_number . '-' . time();

            // Prepare item details untuk Midtrans
            $itemDetails = [];
            foreach ($order->items as $item) {
                $itemDetails[] = [
                    'id' => $item->product_id,
                    'price' => (int) $item->price,
                    'quantity' => $item->quantity,
                    'name' => $item->product_name,
                ];
            }

            // Add shipping cost sebagai item terpisah
            if ($order->shipping_cost > 0) {
                $itemDetails[] = [
                    'id' => 'SHIPPING',
                    'price' => (int) $order->shipping_cost,
                    'quantity' => 1,
                    'name' => 'Biaya Pengiriman',
                ];
            }

            // Transaction details
            $transactionDetails = [
                'order_id' => $transactionId,
                'gross_amount' => (int) $order->total_amount,
            ];

            // Customer details
            $customerDetails = [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
                'shipping_address' => [
                    'address' => $order->shipping_address,
                ],
            ];

            // Enabled payment methods
            $enabledPayments = [
                'qris',           // QRIS dari bank manapun
                'bca_va',         // BCA Virtual Account
                'bni_va',         // BNI Virtual Account
                'bri_va',         // BRI Virtual Account
                'permata_va',     // Permata VA
                'other_va',       // Bank lain (Mandiri, CIMB, dll)
            ];

            // Build Snap parameter
            $params = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
                'enabled_payments' => $enabledPayments,
            ];

            // Get Snap token dari Midtrans
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Simpan payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'transaction_id' => $transactionId,
                'payment_type' => 'pending',
                'amount' => $order->total_amount,
                'status' => 'pending',
                'snap_token' => $snapToken,
                'expired_at' => now()->addHours(24), // 24 jam
            ]);

            return redirect()->route('payment.show', $payment);

        } catch (\Exception $e) {
            Log::error('Midtrans payment creation failed: ' . $e->getMessage());
            
            return back()->with('error', 'Gagal membuat pembayaran. Silakan coba lagi.');
        }
    }

    /**
     * Tampilkan halaman payment
     */
    public function show(Payment $payment)
    {
        $order = $payment->order;

        // Validasi order milik user yang login
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('payment.show', compact('payment', 'order'));
    }

    /**
     * Webhook dari Midtrans untuk notifikasi status payment
     */
    public function notification(Request $request)
    {
        try {
            $notification = new \Midtrans\Notification();

            $transactionStatus = $notification->transaction_status;
            $paymentType = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status ?? null;

            Log::info('Midtrans notification received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
            ]);

            // Find payment by transaction_id
            $payment = Payment::where('transaction_id', $orderId)->firstOrFail();
            $order = $payment->order;

            // Update payment type
            $payment->payment_type = $paymentType;

            // Update status berdasarkan transaction_status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $payment->status = 'settlement';
                    $payment->paid_at = now();
                    $order->status = 'paid';
                }
            } elseif ($transactionStatus == 'settlement') {
                $payment->status = 'settlement';
                $payment->paid_at = now();
                $order->status = 'paid';
            } elseif ($transactionStatus == 'pending') {
                $payment->status = 'pending';
                
                // Simpan VA number atau QR code URL jika ada
                if (isset($notification->va_numbers)) {
                    $payment->va_number = $notification->va_numbers[0]->va_number ?? null;
                    $payment->bank_name = $notification->va_numbers[0]->bank ?? null;
                }
                
                if (isset($notification->actions)) {
                    foreach ($notification->actions as $action) {
                        if ($action->name === 'generate-qr-code') {
                            $payment->qr_code_url = $action->url;
                        }
                    }
                }
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'cancel') {
                $payment->status = $transactionStatus;
                $order->status = 'cancelled';
            } elseif ($transactionStatus == 'expire') {
                $payment->status = 'expire';
                $order->status = 'cancelled';
            }

            // Simpan full response
            $payment->midtrans_response = json_encode($notification);
            $payment->save();
            $order->save();

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Midtrans notification handler failed: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Check status payment (polling dari frontend)
     */
    public function checkStatus(Payment $payment)
    {
        $order = $payment->order;

        // Validasi order milik user yang login
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return response()->json([
            'status' => $payment->status,
            'order_status' => $order->status,
            'paid_at' => $payment->paid_at?->format('Y-m-d H:i:s'),
        ]);
    }
}
