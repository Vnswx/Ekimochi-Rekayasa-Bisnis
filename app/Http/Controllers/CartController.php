<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $customPackages = session('cart_custom_packages', []);
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            
            if ($product) {
                $itemSubtotal = $product->price * $quantity;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal,
                ];
                $subtotal += $itemSubtotal;
            }
        }

        return view('cart.index', compact('cartItems', 'subtotal', 'customPackages'));
    }

    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $validated['quantity'];

        if ($product->stock < $quantity) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id] + $quantity;
            
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Stok tidak mencukupi');
            }
            
            $cart[$product->id] = $newQuantity;
        } else {
            $cart[$product->id] = $quantity;
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $validated['quantity'];

        if ($product->stock < $quantity) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $cart = session('cart', []);
        $cart[$product->id] = $quantity;
        session(['cart' => $cart]);

        return back()->with('success', 'Jumlah produk diupdate');
    }

    public function remove(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Produk dihapus dari keranjang');
    }

    public function clear()
    {
        session()->forget('cart');
        session()->forget('cart_custom_packages');
        
        return back()->with('success', 'Keranjang berhasil dikosongkan');
    }

    public function removeCustomPackage($index)
    {
        $customPackages = session('cart_custom_packages', []);
        
        if (isset($customPackages[$index])) {
            unset($customPackages[$index]);
            $customPackages = array_values($customPackages); // Re-index array
            session(['cart_custom_packages' => $customPackages]);
            
            return back()->with('success', 'Paket custom berhasil dihapus dari keranjang');
        }
        
        return back()->with('error', 'Paket custom tidak ditemukan');
    }
}
