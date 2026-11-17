<?php

namespace App\View\Composers;

use App\Models\Product;
use Illuminate\View\View;

class SearchComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Get all active products for search functionality
        $searchProducts = Product::with('category')
            ->where('status', 'active')
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'image' => asset('storage/' . $product->image),
                    'category' => optional($product->category)->name ?? 'Uncategorized'
                ];
            })
            ->values();

        $view->with('searchProducts', $searchProducts);
    }
}
