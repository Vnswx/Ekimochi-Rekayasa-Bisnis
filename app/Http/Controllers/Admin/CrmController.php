<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CrmController extends Controller
{
    /**
     * Display the CRM dashboard (customer relationship management).
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        // Statistik utama
        $totalCustomers = User::where('role', 'user')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::whereHas('payment', function ($q) {
            $q->where('status', 'settlement');
        })->sum('total_amount');

        // Pelanggan dengan minimal 2 pesanan (pelanggan setia / repeat)
        $repeatCustomers = User::where('role', 'user')
            ->withCount('orders')
            ->get()
            ->filter(fn ($user) => $user->orders_count >= 2)
            ->count();

        // Daftar pelanggan + ringkasan aktivitas pesanan
        $customers = User::where('role', 'user')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->withCount('orders')
            ->withSum(['orders as total_spent' => function ($q) {
                $q->whereHas('payment', function ($p) {
                    $p->where('status', 'settlement');
                });
            }], 'total_amount')
            ->withMax('orders as last_order_at', 'created_at')
            ->orderByDesc('total_spent')
            ->paginate(15)
            ->withQueryString();

        return view('admin.crm.index', compact(
            'customers',
            'search',
            'totalCustomers',
            'totalOrders',
            'totalRevenue',
            'repeatCustomers'
        ));
    }
}
