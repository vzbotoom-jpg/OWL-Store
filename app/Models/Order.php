<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    protected $fillable = [
        'user_id',
        'order_number',
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
        'customer_name',
        'customer_email',
        'customer_phone',
        'notes',
        'coupon_code',
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

    // ========== STATUS CONSTANTS ==========
    
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSED = 'processed';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PENDING = 'pending_verification';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';

    // ========== RELATIONSHIPS ==========
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ========== ACCESSORS ==========
    
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSED => 'Diproses',
            self::STATUS_SHIPPED => 'Dikirim',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => ucfirst($this->status)
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'amber',
            self::STATUS_PROCESSED => 'blue',
            self::STATUS_SHIPPED => 'purple',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_CANCELLED => 'red',
            default => 'gray'
        };
    }

    public function getPaymentStatusLabelAttribute()
    {
        return match($this->payment_status) {
            self::PAYMENT_UNPAID => 'Belum Bayar',
            self::PAYMENT_PENDING => 'Menunggu Verifikasi',
            self::PAYMENT_PAID => 'Lunas',
            self::PAYMENT_FAILED => 'Gagal',
            default => ucfirst($this->payment_status)
        };
    }
}