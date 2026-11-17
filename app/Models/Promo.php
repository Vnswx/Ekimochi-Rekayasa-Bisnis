<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'discount_type',
        'discount_value',
        'min_purchase',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Promo yang sedang berlaku: aktif + dalam periode waktu.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->where(function (Builder $q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    /**
     * Apakah promo sedang berlaku saat ini.
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }
        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }
        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }
        return true;
    }

    /**
     * Label diskon, mis. "10%" atau "Rp 5.000".
     */
    public function discountLabel(): string
    {
        if ($this->discount_type === 'percentage') {
            $value = (float) $this->discount_value;
            return rtrim(rtrim(number_format($value, 1), '0'), '.') . '%';
        }

        return 'Rp ' . number_format($this->discount_value, 0, ',', '.');
    }

    /**
     * Harga setelah diskon.
     */
    public function discountedPrice($price)
    {
        $price = (float) $price;

        if ($this->discount_type === 'percentage') {
            return round($price * (1 - (float) $this->discount_value / 100), 2);
        }

        return max(0, round($price - (float) $this->discount_value, 2));
    }
}