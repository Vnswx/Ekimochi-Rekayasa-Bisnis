<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard with products and categories.
     */
    public function index(Request $request): View
    {
        // Get all products grouped by category (exclude Paket Box from filter categories)
        $products = Product::with('category')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get categories for filter (only Classic, Original Series, Premium)
        $filterCategories = Category::whereIn('name', ['Classic', 'Original Series', 'Premium'])
            ->withCount(['products' => function($query) {
                $query->where('status', 'active');
            }])
            ->get();

        // Get all categories including Paket Box
        $allCategories = Category::withCount(['products' => function($query) {
            $query->where('status', 'active');
        }])->get();

        // Get featured products by category (max 6 per category for initial display)
        $classicProducts = $products->where('category.name', 'Classic')->take(6);
        $originalProducts = $products->where('category.name', 'Original Series')->take(6);
        $premiumProducts = $products->where('category.name', 'Premium')->take(6);
        
        // Get box packages
        $boxPackages = $products->where('category.name', 'Paket Box');

        return view('dashboard', compact(
            'products', 
            'filterCategories', 
            'allCategories',
            'classicProducts',
            'originalProducts',
            'premiumProducts',
            'boxPackages'
        ));
    }

    /**
     * Show the admin dashboard.
     */
    public function adminDashboard(): View
    {
        // Product statistics
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'active')->count();
        $inactiveProducts = Product::where('status', 'inactive')->count();
        $lowStockProducts = Product::where('stock', '>', 0)->where('stock', '<', 10)->count();
        $outOfStockProducts = Product::where('stock', 0)->count();
        
        $totalCategories = Category::count();
        $categories = Category::withCount('products')->get();
        
        $recentProducts = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Sales statistics
        $totalOrders = \App\Models\Order::count();
        $totalRevenue = \App\Models\Order::whereHas('payment', function($q) {
            $q->where('status', 'settlement');
        })->sum('total_amount');
        
        $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
        $processingOrders = \App\Models\Order::where('status', 'processing')->count();
        $completedOrders = \App\Models\Order::where('status', 'completed')->count();
        
        // Today's sales
        $todayOrders = \App\Models\Order::whereDate('created_at', today())->count();
        $todayRevenue = \App\Models\Order::whereDate('created_at', today())
            ->whereHas('payment', function($q) {
                $q->where('status', 'settlement');
            })->sum('total_amount');
        
        // Recent orders
        $recentOrders = \App\Models\Order::with(['user', 'payment', 'items'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalProducts',
            'activeProducts',
            'inactiveProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'totalCategories',
            'categories',
            'recentProducts',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'todayOrders',
            'todayRevenue',
            'recentOrders'
        ));
    }
}
