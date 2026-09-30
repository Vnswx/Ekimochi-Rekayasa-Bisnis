<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = session('cart', []);
        $customPackages = session('cart_custom_packages', []);

        if (empty($cart) && empty($customPackages)) {
            return redirect()->route('catalog.index')
                ->with('error', 'Keranjang belanja kosong');
        }

        $cartItems = [];
        $subtotal = 0;

        // Process regular products
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            
            if ($product && $product->status === 'active') {
                $itemSubtotal = $product->price * $quantity;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal,
                ];
                $subtotal += $itemSubtotal;
            }
        }

        // Process custom packages
        foreach ($customPackages as $package) {
            $subtotal += $package['total_price'];
        }

        $shippingCost = 15000;
        $total = $subtotal + $shippingCost;
        $user = Auth::user();

        return view('checkout.index', compact('cartItems', 'customPackages', 'subtotal', 'shippingCost', 'total', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cart = session('cart', []);
        $customPackages = session('cart_custom_packages', []);

        if (empty($cart) && empty($customPackages)) {
            return redirect()->route('catalog.index')
                ->with('error', 'Keranjang belanja kosong');
        }

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $orderItems = [];

            // Process regular products
            foreach ($cart as $productId => $quantity) {
                $product = Product::findOrFail($productId);
                
                if ($product->stock < $quantity) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi");
                }

                $itemSubtotal = $product->price * $quantity;
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ];
            }

            // Process custom packages
            foreach ($customPackages as $package) {
                $subtotal += $package['total_price'];
                
                // Add custom package as a single order item
                $orderItems[] = [
                    'product' => null,
                    'is_custom_package' => true,
                    'custom_package_data' => $package,
                    'quantity' => 1,
                    'price' => $package['total_price'],
                    'subtotal' => $package['total_price'],
                ];
            }

            $shippingCost = 15000;
            $totalAmount = $subtotal + $shippingCost;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($orderItems as $item) {
                if (isset($item['is_custom_package']) && $item['is_custom_package']) {
                    // Save custom package as order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => null,
                        'product_name' => 'Paket Custom - ' . $item['custom_package_data']['box_name'],
                        'product_sku' => 'CUSTOM-PKG-' . $order->order_number,
                        'price' => $item['price'],
                        'quantity' => 1,
                        'subtotal' => $item['subtotal'],
                        'notes' => json_encode($item['custom_package_data']), // Store package details in notes
                    ]);
                } else {
                    // Regular product
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product']->id,
                        'product_name' => $item['product']->name,
                        'product_sku' => $item['product']->sku,
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $item['product']->decrement('stock', $item['quantity']);
                }
            }

            DB::commit();

            session()->forget('cart');
            session()->forget('cart_custom_packages');

            return redirect()->route('payment.create', $order)
                ->with('success', 'Order berhasil dibuat. Silakan lanjutkan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Gagal membuat order: ' . $e->getMessage());
        }
    }
}
