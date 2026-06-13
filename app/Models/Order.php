<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    protected $fillable = [
    'user_id',
    'order_number',
    'customer_name',
    'customer_email',
    'customer_phone',
    'subtotal',
    'discount',
    'shipping_cost',
    'total',
    'status',
    'payment_method',
    'payment_status',
    'shipping_address',
    'shipping_city',
    'shipping_province',
    'shipping_postal_code',
    'shipping_courier',
    'notes',
    'coupon_code',
    'coupon_id',
    'resi',
    'payment_proof',
    'paid_at'
];

    protected $casts = [
        'subtotal' => 'integer',
        'discount' => 'integer',
        'shipping_cost' => 'integer',
        'total' => 'integer',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSED = 'processed';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_PENDING_VERIFICATION = 'pending_verification';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}