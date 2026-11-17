<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /**
     * Display analytics & statistics page.
     */
    public function index(): View
    {
        // ===== Ringkasan =====
        $totalRevenue = Order::whereHas('payment', fn ($q) => $q->where('status', 'settlement'))->sum('total_amount');
        $totalOrders = Order::count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $totalCustomers = User::where('role', 'user')->count();

        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())
            ->whereHas('payment', fn ($q) => $q->where('status', 'settlement'))
            ->sum('total_amount');

        // ===== Pendapatan & jumlah pesanan 14 hari terakhir =====
        $days = collect(range(13, 0))->map(fn ($i) => Carbon::today()->subDays($i));
        $start = $days->first()->startOfDay();

        $revenueRows = DB::table('orders')
            ->join('payments', 'payments.order_id', '=', 'orders.id')
            ->where('payments.status', 'settlement')
            ->where('orders.created_at', '>=', $start)
            ->selectRaw('DATE(orders.created_at) as tanggal, SUM(orders.total_amount) as total')
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $orderRows = Order::where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah')
            ->groupBy('tanggal')
            ->pluck('jumlah', 'tanggal');

        $revenueLabels = $days->map(fn ($d) => $d->format('d M'));
        $revenueValues = $days->map(fn ($d) => (float) ($revenueRows[$d->format('Y-m-d')] ?? 0));
        $orderValues = $days->map(fn ($d) => (int) ($orderRows[$d->format('Y-m-d')] ?? 0));

        // ===== Distribusi tipe pesanan =====
        $orderTypeRaw = Order::selectRaw('order_type, COUNT(*) as jumlah')
            ->groupBy('order_type')
            ->pluck('jumlah', 'order_type');
        $typeLabels = ['dine_in' => 'Makan di Tempat', 'take_away' => 'Bawa Pulang', 'delivery' => 'Pengiriman'];
        $orderTypeLabels = collect($typeLabels)->values();
        $orderTypeValues = collect($typeLabels)->keys()->map(fn ($k) => (int) ($orderTypeRaw[$k] ?? 0));

        // ===== Distribusi status pesanan =====
        $statusRaw = Order::selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');
        $statusLabels = [
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'ready' => 'Siap',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
        $orderStatusLabels = collect($statusLabels)->values();
        $orderStatusValues = collect($statusLabels)->keys()->map(fn ($k) => (int) ($statusRaw[$k] ?? 0));

        // ===== Distribusi status pembayaran =====
        $payRaw = Payment::selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');
        $payLabels = [
            'settlement' => 'Lunas',
            'pending' => 'Menunggu',
            'expire' => 'Kadaluarsa',
            'cancel' => 'Dibatalkan',
            'deny' => 'Ditolak',
        ];
        $paymentLabels = collect($payLabels)->values();
        $paymentValues = collect($payLabels)->keys()->map(fn ($k) => (int) ($payRaw[$k] ?? 0));

        // ===== Produk terlaris (top 5) =====
        $topProducts = OrderItem::selectRaw('product_name, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();
        $topProductLabels = $topProducts->pluck('product_name')->values();
        $topProductValues = $topProducts->pluck('total_qty')->map(fn ($v) => (int) $v)->values();
        $topProductRevenue = $topProducts->pluck('total_revenue')->map(fn ($v) => (float) $v)->values();

        // ===== Pelanggan terbaik (top 5, total belanja lunas) =====
        $topCustomers = User::where('role', 'user')
            ->withSum(['orders as total_spent' => fn ($q) => $q->whereHas('payment', fn ($p) => $p->where('status', 'settlement'))], 'total_amount')
            ->withCount('orders')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();
        $topCustomerLabels = $topCustomers->pluck('name')->values();
        $topCustomerValues = $topCustomers->pluck('total_spent')->map(fn ($v) => (float) ($v ?? 0))->values();
        $topCustomerOrders = $topCustomers->pluck('orders_count')->values();

        return view('admin.analytics.index', compact(
            'totalRevenue',
            'totalOrders',
            'avgOrderValue',
            'totalCustomers',
            'todayOrders',
            'todayRevenue',
            'revenueLabels',
            'revenueValues',
            'orderValues',
            'orderTypeLabels',
            'orderTypeValues',
            'orderStatusLabels',
            'orderStatusValues',
            'paymentLabels',
            'paymentValues',
            'topProductLabels',
            'topProductValues',
            'topProductRevenue',
            'topCustomerLabels',
            'topCustomerValues',
            'topCustomerOrders'
        ));
    }
}