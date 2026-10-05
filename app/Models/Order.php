<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'order_type',
        'outlet_id',
        'outlet_table_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'subtotal',
        'shipping_cost',
        'delivery_distance',
        'total_amount',
        'estimated_ready_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'delivery_distance' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'estimated_ready_time' => 'datetime',
    ];

    public static function generateOrderNumber(): string
    {
        $date = date('Ymd');
        $lastOrder = self::whereDate('created_at', today())->latest()->first();
        $number = $lastOrder ? (int) substr($lastOrder->order_number, -3) + 1 : 1;
        
        return 'INV-' . $date . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function outletTable(): BelongsTo
    {
        return $this->belongsTo(OutletTable::class);
    }

    public function isPaid(): bool
    {
        return $this->payment && $this->payment->status === 'settlement';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isDineIn(): bool
    {
        return $this->order_type === 'dine_in';
    }

    public function isTakeAway(): bool
    {
        return $this->order_type === 'take_away';
    }

    public function isDelivery(): bool
    {
        return $this->order_type === 'delivery';
    }
}
