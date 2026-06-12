<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    protected $table = 'wishlists';
    
    protected $fillable = [
        'user_id',
        'product_id',
        'session_id',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    // ========== SCOPES ==========
    
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForGuest($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeWithProductDetails($query)
    {
        return $query->with(['product' => function($q) {
            $q->with('category')->active();
        }]);
    }

    // ========== HELPERS ==========
    
    public static function isInWishlist($userId, $productId): bool
    {
        return self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();
    }

    public static function getWishlistCount($userId): int
    {
        return self::where('user_id', $userId)->count();
    }

    public static function addToWishlist($userId, $productId, $sessionId = null): self
    {
        return self::firstOrCreate([
            'user_id' => $userId,
            'product_id' => $productId,
        ], [
            'session_id' => $sessionId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function removeFromWishlist($userId, $productId): bool
    {
        return self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();
    }

    public static function syncGuestWishlist($userId, $sessionId): void
    {
        // Move guest wishlist items to user
        $guestItems = self::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->get();
        
        foreach ($guestItems as $item) {
            self::updateOrCreate(
                ['user_id' => $userId, 'product_id' => $item->product_id],
                ['ip_address' => $item->ip_address, 'user_agent' => $item->user_agent]
            );
            $item->delete();
        }
    }
}