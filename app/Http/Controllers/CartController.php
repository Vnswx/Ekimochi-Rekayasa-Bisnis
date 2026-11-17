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

        $promoService = new \App\Services\PromoService();
        $promo = $promoService->activePromo();

        // Pass 1: hitung subtotal asli (sebelum diskon) produk reguler
        $rawProductSubtotal = 0;
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $rawProductSubtotal += (float) $product->price * $quantity;
            }
        }

        $promoApplied = $promoService->applies($promo, $rawProductSubtotal);
        $subtotal = 0;

        // Pass 2: bangun item dengan harga diskon jika promo berlaku
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);

            if ($product) {
                $unitPrice = $promoApplied && $promo
                    ? $promoService->unitPrice($promo, $product->price)
                    : (float) $product->price;
                $itemSubtotal = round($unitPrice * $quantity, 2);
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $itemSubtotal,
                ];
                $subtotal += $itemSubtotal;
            }
        }

        $discount = $promoApplied && $promo
            ? $promoService->discountFor($promo, $rawProductSubtotal)
            : 0;

        $promoNotice = null;
        if ($promo && !$promoApplied) {
            $promoNotice = "Promo {$promo->name} berlaku untuk min. belanja Rp "
                . number_format($promo->min_purchase, 0, ',', '.');
        }

        return view('cart.index', compact(
            'cartItems',
            'subtotal',
            'customPackages',
            'promo',
            'promoApplied',
            'discount',
            'promoNotice'
        ));
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
