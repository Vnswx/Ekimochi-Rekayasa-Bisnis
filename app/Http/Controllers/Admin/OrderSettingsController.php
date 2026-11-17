<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderSettingsController extends Controller
{
    /**
     * Display order & delivery management page.
     */
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'payment'])
            ->orderBy('created_at', 'desc');

        // Filter status pesanan
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tipe pesanan / pengiriman
        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        // Filter status pembayaran
        if ($request->filled('payment_status')) {
            $query->whereHas('payment', function ($q) use ($request) {
                $q->where('status', $request->payment_status);
            });
        }

        // Pencarian nomor pesanan / pelanggan
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        // Statistik
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::whereIn('status', ['processing', 'ready'])->count();
        $deliveryOrders = Order::where('order_type', 'delivery')->count();

        return view('admin.orderSettings.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'deliveryOrders'
        ));
    }

    /**
     * Update order status directly from the management page.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        return back()->with(
            'success',
            "Status pesanan {$order->order_number} diubah dari {$oldStatus} ke {$validated['status']}"
        );
    }
}