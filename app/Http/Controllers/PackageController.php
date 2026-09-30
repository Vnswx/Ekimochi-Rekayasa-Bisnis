<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        // Ambil category Paket Box
        $paketCategory = Category::where('name', 'like', '%Paket Box%')
                                 ->orWhere('name', 'like', '%Box%')
                                 ->pluck('id');
        
        $packages = Product::whereIn('category_id', $paketCategory)
                          ->where('status', 'active')
                          ->with('category')
                          ->get();
        
        return view('packages.index', compact('packages'));
    }

    public function show(Product $package)
    {
        // Validasi bahwa ini adalah product dari category paket box
        $paketCategory = Category::where('name', 'like', '%Paket Box%')
                                 ->orWhere('name', 'like', '%Box%')
                                 ->pluck('id');
        
        if (!$paketCategory->contains($package->category_id)) {
            abort(404);
        }
        
        return view('packages.show', compact('package'));
    }

    public function customBuilder()
    {
        // Ambil semua produk non-paket untuk custom builder
        $paketCategory = Category::where('name', 'like', '%Paket Box%')
                                 ->orWhere('name', 'like', '%Box%')
                                 ->pluck('id');
        
        $products = Product::whereNotIn('category_id', $paketCategory)
                          ->where('status', 'active')
                          ->where('stock', '>', 0)
                          ->with('category')
                          ->get();
        
        // Box size options
        $boxSizes = [
            'small' => ['name' => 'Box Kecil', 'capacity' => 6, 'price' => 5000],
            'medium' => ['name' => 'Box Sedang', 'capacity' => 12, 'price' => 8000],
            'large' => ['name' => 'Box Besar', 'capacity' => 24, 'price' => 12000],
        ];
        
        return view('packages.custom', compact('products', 'boxSizes'));
    }

    public function addToCart(Request $request, Product $package)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Validasi stock
        if ($package->stock < $validated['quantity']) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        // Gunakan cart biasa (bukan cart_packages terpisah)
        $cart = session('cart', []);
        
        if (isset($cart[$package->id])) {
            $cart[$package->id] += $validated['quantity'];
        } else {
            $cart[$package->id] = $validated['quantity'];
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Paket berhasil ditambahkan ke keranjang');
    }

    public function addCustomToCart(Request $request)
    {
        $validated = $request->validate([
            'box_size' => 'required|in:small,medium,large',
            'products' => 'required|array',
            'products.*.id' => 'nullable|exists:products,id',
            'products.*.quantity' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // Filter produk yang quantity > 0 dan id ada
        $selectedProducts = [];
        foreach($validated['products'] as $product) {
            if (isset($product['id']) && isset($product['quantity']) && $product['quantity'] > 0) {
                $selectedProducts[] = $product;
            }
        }

        if (empty($selectedProducts)) {
            return back()->with('error', 'Pilih minimal 1 produk untuk paket custom');
        }

        // Hitung total items
        $totalItems = array_sum(array_column($selectedProducts, 'quantity'));
        
        // Validasi kapasitas box
        $boxSizes = [
            'small' => ['name' => 'Box Kecil', 'capacity' => 6, 'price' => 5000],
            'medium' => ['name' => 'Box Sedang', 'capacity' => 12, 'price' => 8000],
            'large' => ['name' => 'Box Besar', 'capacity' => 24, 'price' => 12000],
        ];
        
        $selectedBoxSize = $boxSizes[$validated['box_size']];
        
        if ($totalItems > $selectedBoxSize['capacity']) {
            return back()->with('error', "Total produk ($totalItems) melebihi kapasitas box ({$selectedBoxSize['capacity']})");
        }

        // Load product details
        $productIds = array_column($selectedProducts, 'id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        
        // Hitung total harga
        $totalPrice = $selectedBoxSize['price'];
        foreach($selectedProducts as $item) {
            $product = $products[$item['id']];
            $totalPrice += $product->price * $item['quantity'];
        }

        // Simpan ke session
        $customPackage = [
            'box_size' => $validated['box_size'],
            'box_name' => $selectedBoxSize['name'],
            'box_price' => $selectedBoxSize['price'],
            'box_capacity' => $selectedBoxSize['capacity'],
            'products' => $selectedProducts,
            'product_details' => $products->toArray(),
            'total_items' => $totalItems,
            'total_price' => $totalPrice,
            'notes' => $validated['notes'] ?? null,
            'is_custom' => true,
            'is_preorder' => true,
            'preorder_days' => 3,
            'created_at' => now()->toDateTimeString(),
        ];

        $cart = session('cart_custom_packages', []);
        $cart[] = $customPackage;
        session(['cart_custom_packages' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Paket custom berhasil ditambahkan ke keranjang! (Pre-Order 3 hari kerja)');
    }
}
