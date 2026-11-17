<?php

namespace App\Services;

use App\Models\Promo;

class PromoService
{
    /**
     * Promo yang sedang berlaku (terbaru).
     */
    public function activePromo(): ?Promo
    {
        return Promo::active()->orderBy('created_at', 'desc')->first();
    }

    /**
     * Apakah promo berlaku untuk subtotal tertentu (syarat min_purchase).
     */
    public function applies(?Promo $promo, float $rawSubtotal): bool
    {
        if (!$promo) {
            return false;
        }

        return (float) $promo->min_purchase <= 0 || $rawSubtotal >= (float) $promo->min_purchase;
    }

    /**
     * Harga satuan setelah diskon.
     */
    public function unitPrice(Promo $promo, float $originalPrice): float
    {
        return (float) $promo->discountedPrice($originalPrice);
    }

    /**
     * Total potongan untuk subtotal asli.
     */
    public function discountFor(Promo $promo, float $originalSubtotal): float
    {
        return round($originalSubtotal - (float) $promo->discountedPrice($originalSubtotal), 2);
    }
}