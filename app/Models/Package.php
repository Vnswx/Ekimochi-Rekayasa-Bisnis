<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'total_items',
        'box_size',
        'image',
        'status',
        'is_preorder',
        'preorder_days',
    ];

    protected $casts = [
        'is_preorder' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(PackageItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'package_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
