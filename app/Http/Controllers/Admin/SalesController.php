<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesController extends Controller
{
    /**
     * Display a listing of sales/orders with filters
     */
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items', 'payment', 'outlet', 'outletTable'])
            ->orderBy('created_at', 'desc');

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->whereHas('payment', function($q) use ($request) {
                $q->where('status', $request->payment_status);
            });
        }

        // Filter by order type
        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        // Statistics for summary cards
        $totalOrders = Order::count();
        $totalRevenue = Order::whereHas('payment', function($q) {
            $q->where('status', 'settlement');
        })->sum('total_amount');
        
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();

        return view('admin.sales.index', compact(
            'orders',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'completedOrders'
        ));
    }

    /**
     * Display the specified order detail
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'items.product', 'payment', 'outlet', 'outletTable']);

        return view('admin.sales.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,ready,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        return redirect()
            ->route('admin.sales.show', $order)
            ->with('success', "Status order berhasil diubah dari {$oldStatus} ke {$validated['status']}");
    }
}
