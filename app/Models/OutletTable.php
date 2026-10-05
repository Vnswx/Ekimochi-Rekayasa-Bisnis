<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'outlet_id',
        'table_number',
        'qr_token',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Generate unique QR token
     */
    public static function generateQrToken()
    {
        do {
            $token = \Illuminate\Support\Str::random(32);
        } while (self::where('qr_token', $token)->exists());

        return $token;
    }
}
