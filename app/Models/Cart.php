<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $table = 'carts';
    
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'price',
        'variant'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ========== RELATIONSHIPS ==========
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ========== ACCESSORS ==========
    
    public function getSubtotalAttribute(): int
    {
        return $this->price * $this->quantity;
    }

    // ========== SCOPES ==========
    
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    // ========== HELPERS ==========
    
    public static function getCartItems($userId, $sessionId)
    {
        return self::with('product')
            ->where(function($q) use ($userId, $sessionId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('session_id', $sessionId);
                }
            })
            ->get();
    }
}