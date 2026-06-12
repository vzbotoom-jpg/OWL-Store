<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $table = 'activities';
    
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'action',
        'details',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== ACTION CONSTANTS ==========
    
    const ACTION_LOGIN = 'login';
    const ACTION_LOGOUT = 'logout';
    const ACTION_REGISTER = 'register';
    const ACTION_VIEW_PRODUCT = 'view_product';
    const ACTION_ADD_TO_CART = 'add_to_cart';
    const ACTION_CHECKOUT = 'checkout';
    const ACTION_ORDER = 'order';
    const ACTION_CANCEL_ORDER = 'cancel_order';
    const ACTION_ADD_REVIEW = 'add_review';
    const ACTION_ADD_TO_WISHLIST = 'add_to_wishlist';
    const ACTION_UPDATE_PROFILE = 'update_profile';
    const ACTION_CHANGE_PASSWORD = 'change_password';

    // ========== RELATIONSHIPS ==========
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ========== SCOPES ==========
    
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeLastDays($query, $days)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // ========== ACCESSORS ==========
    
    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            self::ACTION_LOGIN => 'Login',
            self::ACTION_LOGOUT => 'Logout',
            self::ACTION_REGISTER => 'Register',
            self::ACTION_VIEW_PRODUCT => 'Melihat Produk',
            self::ACTION_ADD_TO_CART => 'Menambah ke Keranjang',
            self::ACTION_CHECKOUT => 'Checkout',
            self::ACTION_ORDER => 'Order',
            self::ACTION_CANCEL_ORDER => 'Membatalkan Order',
            self::ACTION_ADD_REVIEW => 'Menambah Review',
            self::ACTION_ADD_TO_WISHLIST => 'Menambah ke Wishlist',
            self::ACTION_UPDATE_PROFILE => 'Update Profil',
            self::ACTION_CHANGE_PASSWORD => 'Ganti Password',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }

    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            self::ACTION_LOGIN => 'ti-login',
            self::ACTION_LOGOUT => 'ti-logout',
            self::ACTION_REGISTER => 'ti-user-plus',
            self::ACTION_VIEW_PRODUCT => 'ti-eye',
            self::ACTION_ADD_TO_CART => 'ti-shopping-cart',
            self::ACTION_CHECKOUT => 'ti-credit-card',
            self::ACTION_ORDER => 'ti-package',
            self::ACTION_CANCEL_ORDER => 'ti-x',
            self::ACTION_ADD_REVIEW => 'ti-star',
            self::ACTION_ADD_TO_WISHLIST => 'ti-heart',
            self::ACTION_UPDATE_PROFILE => 'ti-user-edit',
            self::ACTION_CHANGE_PASSWORD => 'ti-lock',
            default => 'ti-activity',
        };
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d M Y H:i:s');
    }

    // ========== HELPERS ==========
    
    public static function log($userId, $action, $details = null, $metadata = null): self
    {
        return self::create([
            'user_id' => $userId,
            'session_id' => session()->getId(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'action' => $action,
            'details' => $details,
            'metadata' => $metadata,
        ]);
    }

    public static function getUserActivities($userId, $limit = 50)
    {
        return self::forUser($userId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public static function getRecentActivities($limit = 100)
    {
        return self::with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public static function getStatsByAction($startDate = null, $endDate = null)
    {
        $query = self::query();
        
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        return $query->selectRaw('action, COUNT(*) as total')
            ->groupBy('action')
            ->pluck('total', 'action')
            ->toArray();
    }

    public static function cleanOldActivities($days = 30)
    {
        return self::where('created_at', '<', now()->subDays($days))->delete();
    }
}