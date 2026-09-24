<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'source_id',
        'barcode',
        'name',
        'category_id',
        'sell_price',
        'stock',
        'unit',
        'is_active',
        'synced_at',
    ];

    protected function casts(): array
    {
        return [
            'sell_price' => 'decimal:2',
            'stock' => 'decimal:3',
            'is_active' => 'boolean',
            'synced_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
