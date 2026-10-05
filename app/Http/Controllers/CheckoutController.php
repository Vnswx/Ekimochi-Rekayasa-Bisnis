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

    public function index(Request $request)
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

        // Get outlets for selection
        $outlets = \App\Models\Outlet::active()->get();
        
        // Check if coming from QR scan
        $qrToken = $request->query('qr');
        $selectedTable = null;
        $selectedOutlet = null;
        
        if ($qrToken) {
            $selectedTable = \App\Models\OutletTable::where('qr_token', $qrToken)
                ->where('is_active', true)
                ->with('outlet')
                ->first();
                
            if ($selectedTable) {
                $selectedOutlet = $selectedTable->outlet;
            }
        }

        $shippingCost = 0; // Default untuk dine_in dan take_away
        $total = $subtotal + $shippingCost;
        $user = Auth::user();

        return view('checkout.index', compact(
            'cartItems', 
            'customPackages', 
            'subtotal', 
            'shippingCost', 
            'total', 
            'user',
            'outlets',
            'selectedTable',
            'selectedOutlet'
        ));
    }

    public function store(Request $request)
    {
        // Base validation
        $rules = [
            'order_type' => 'required|in:dine_in,take_away,delivery',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ];

        // Conditional validation based on order_type
        if ($request->order_type === 'dine_in') {
            $rules['qr_token'] = 'required|exists:outlet_tables,qr_token';
        } elseif ($request->order_type === 'take_away') {
            $rules['outlet_id'] = 'required|exists:outlets,id';
        } elseif ($request->order_type === 'delivery') {
            $rules['shipping_address'] = 'required|string|max:500';
            $rules['latitude'] = 'nullable|numeric';
            $rules['longitude'] = 'nullable|numeric';
        }

        $validated = $request->validate($rules);

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
            $totalItemCount = 0;

            // Process regular products
            foreach ($cart as $productId => $quantity) {
                $product = Product::findOrFail($productId);
                
                if ($product->stock < $quantity) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi");
                }

                $itemSubtotal = $product->price * $quantity;
                $subtotal += $itemSubtotal;
                $totalItemCount += $quantity;

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
                $totalItemCount += $package['total_items'];
                
                $orderItems[] = [
                    'product' => null,
                    'is_custom_package' => true,
                    'custom_package_data' => $package,
                    'quantity' => 1,
                    'price' => $package['total_price'],
                    'subtotal' => $package['total_price'],
                ];
            }

            // Determine outlet, shipping cost, and delivery distance
            $outletId = null;
            $outletTableId = null;
            $shippingCost = 0;
            $deliveryDistance = null;
            $shippingAddress = null;

            if ($validated['order_type'] === 'dine_in') {
                $table = \App\Models\OutletTable::where('qr_token', $validated['qr_token'])
                    ->where('is_active', true)
                    ->firstOrFail();
                $outletId = $table->outlet_id;
                $outletTableId = $table->id;
                $shippingCost = 0;
            } elseif ($validated['order_type'] === 'take_away') {
                $outletId = $validated['outlet_id'];
                $shippingCost = 0;
            } elseif ($validated['order_type'] === 'delivery') {
                $shippingAddress = $validated['shipping_address'];
                
                // Find nearest outlet and calculate shipping cost
                if (isset($validated['latitude']) && isset($validated['longitude'])) {
                    $nearestOutlet = $this->findNearestOutlet($validated['latitude'], $validated['longitude']);
                    
                    if (!$nearestOutlet) {
                        throw new \Exception('Tidak ada outlet terdekat yang ditemukan');
                    }
                    
                    $deliveryDistance = $nearestOutlet->distanceTo($validated['latitude'], $validated['longitude']);
                    
                    if ($deliveryDistance > 15) {
                        throw new \Exception('Maaf, lokasi Anda terlalu jauh dari outlet kami (maksimal 15 km). Jarak: ' . number_format($deliveryDistance, 2) . ' km');
                    }
                    
                    // Calculate shipping cost based on distance
                    if ($deliveryDistance <= 5) {
                        $shippingCost = 15000;
                    } elseif ($deliveryDistance <= 10) {
                        $shippingCost = 25000;
                    } elseif ($deliveryDistance <= 15) {
                        $shippingCost = 35000;
                    }
                    
                    $outletId = $nearestOutlet->id;
                } else {
                    // Default jika tidak ada koordinat
                    $shippingCost = 15000;
                    $outletId = \App\Models\Outlet::active()->first()->id;
                }
            }

            $totalAmount = $subtotal + $shippingCost;

            // Calculate estimated ready time
            $estimatedReadyTime = $this->calculateEstimatedReadyTime($validated['order_type'], $totalItemCount);

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'order_type' => $validated['order_type'],
                'outlet_id' => $outletId,
                'outlet_table_id' => $outletTableId,
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $shippingAddress,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'delivery_distance' => $deliveryDistance,
                'total_amount' => $totalAmount,
                'estimated_ready_time' => $estimatedReadyTime,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($orderItems as $item) {
                if (isset($item['is_custom_package']) && $item['is_custom_package']) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => null,
                        'product_name' => 'Paket Custom - ' . $item['custom_package_data']['box_name'],
                        'product_sku' => 'CUSTOM-PKG-' . $order->order_number,
                        'price' => $item['price'],
                        'quantity' => 1,
                        'subtotal' => $item['subtotal'],
                        'notes' => json_encode($item['custom_package_data']),
                    ]);
                } else {
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

    /**
     * Find nearest outlet based on coordinates
     */
    private function findNearestOutlet($lat, $lon)
    {
        $outlets = \App\Models\Outlet::active()->get();
        $nearestOutlet = null;
        $minDistance = PHP_FLOAT_MAX;

        foreach ($outlets as $outlet) {
            $distance = $outlet->distanceTo($lat, $lon);
            if ($distance !== null && $distance < $minDistance) {
                $minDistance = $distance;
                $nearestOutlet = $outlet;
            }
        }

        return $nearestOutlet;
    }

    /**
     * Calculate estimated ready time
     * Base time + 2 minutes per item
     */
    private function calculateEstimatedReadyTime($orderType, $itemCount)
    {
        $baseMinutes = [
            'dine_in' => 15,
            'take_away' => 20,
            'delivery' => 30,
        ];

        $base = $baseMinutes[$orderType] ?? 20;
        $additional = $itemCount * 2;
        $totalMinutes = $base + $additional;

        return now()->addMinutes($totalMinutes);
    }
}
