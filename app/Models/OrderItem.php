<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    
    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'price',
        'quantity',
        'subtotal',
        'variant'
    ];

    protected $casts = [
        'price' => 'integer',
        'quantity' => 'integer',
        'subtotal' => 'integer'
    ];

    // ========== RELATIONSHIPS ==========
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}