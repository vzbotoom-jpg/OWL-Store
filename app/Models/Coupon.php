<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';
    
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_spend',
        'max_discount',
        'usage_limit',
        'used_count',
        'per_user_limit',
        'starts_at',
        'ends_at',
        'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_spend' => 'integer',
        'max_discount' => 'integer',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'per_user_limit' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    // ========== SCOPES ==========
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    public function scopeValid($query)
    {
        return $query->active()
            ->where(function($q) {
                $q->whereNull('usage_limit')
                  ->orWhereRaw('used_count < usage_limit');
            });
    }

    // ========== ACCESSORS ==========
    
    public function getDiscountDisplayAttribute()
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        }
        return 'Rp ' . number_format($this->value, 0, ',', '.');
    }

    public function getIsValidAttribute()
    {
        return $this->is_active && 
               $this->starts_at <= now() && 
               $this->ends_at >= now() &&
               ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }

    // ========== HELPERS ==========
    
    public function calculateDiscount($subtotal)
    {
        $discount = 0;
        
        if ($this->type === 'percentage') {
            $discount = $subtotal * ($this->value / 100);
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        } else if ($this->type === 'nominal') {
            $discount = $this->value;
        }
        
        return min($discount, $subtotal);
    }

    public function canBeUsedByUser($userId)
    {
        if (!$this->per_user_limit) {
            return true;
        }
        
        $userUsedCount = Order::where('user_id', $userId)
            ->where('coupon_code', $this->code)
            ->count();
        
        return $userUsedCount < $this->per_user_limit;
    }
}