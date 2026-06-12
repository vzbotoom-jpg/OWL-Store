<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSession extends Model
{
    protected $table = 'user_sessions';
    
    protected $fillable = [
        'user_id',
        'session_id',
        'device_name',
        'device_type',
        'browser',
        'os',
        'ip_address',
        'user_agent',
        'location',
        'is_current',
        'last_activity',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'last_activity' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== DEVICE TYPE CONSTANTS ==========
    
    const DEVICE_MOBILE = 'mobile';
    const DEVICE_TABLET = 'tablet';
    const DEVICE_DESKTOP = 'desktop';

    const BROWSER_CHROME = 'Chrome';
    const BROWSER_FIREFOX = 'Firefox';
    const BROWSER_SAFARI = 'Safari';
    const BROWSER_EDGE = 'Edge';
    const BROWSER_OPERA = 'Opera';
    const BROWSER_OTHER = 'Other';

    const OS_WINDOWS = 'Windows';
    const OS_MAC = 'macOS';
    const OS_LINUX = 'Linux';
    const OS_ANDROID = 'Android';
    const OS_IOS = 'iOS';
    const OS_OTHER = 'Other';

    // ========== RELATIONSHIPS ==========
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ========== SCOPES ==========
    
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeMobile($query)
    {
        return $query->where('device_type', self::DEVICE_MOBILE);
    }

    public function scopeDesktop($query)
    {
        return $query->where('device_type', self::DEVICE_DESKTOP);
    }

    public function scopeActive($query)
    {
        return $query->where('last_activity', '>=', now()->subMinutes(30));
    }

    public function scopeInactive($query)
    {
        return $query->where('last_activity', '<', now()->subMinutes(30));
    }

    // ========== ACCESSORS ==========
    
    public function getDeviceTypeLabelAttribute(): string
    {
        return match($this->device_type) {
            self::DEVICE_MOBILE => 'Mobile',
            self::DEVICE_TABLET => 'Tablet',
            self::DEVICE_DESKTOP => 'Desktop',
            default => 'Unknown',
        };
    }

    public function getDeviceTypeIconAttribute(): string
    {
        return match($this->device_type) {
            self::DEVICE_MOBILE => 'ti-device-mobile',
            self::DEVICE_TABLET => 'ti-device-tablet',
            self::DEVICE_DESKTOP => 'ti-device-desktop',
            default => 'ti-device-unknown',
        };
    }

    public function getLastActivityAgoAttribute(): string
    {
        return $this->last_activity ? $this->last_activity->diffForHumans() : 'Never';
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->last_activity && $this->last_activity->diffInMinutes() <= 30;
    }

    public function getFormattedLocationAttribute(): string
    {
        if ($this->location) {
            return $this->location;
        }
        
        // Fallback based on IP
        return $this->ip_address === '127.0.0.1' ? 'Localhost' : 'Unknown Location';
    }

    // ========== HELPERS ==========
    
    public static function parseUserAgent($userAgent): array
    {
        $data = [
            'device_type' => self::DEVICE_DESKTOP,
            'browser' => self::BROWSER_OTHER,
            'os' => self::OS_OTHER,
            'device_name' => 'Unknown',
        ];
        
        if (preg_match('/(iPhone|iPad|iPod)/i', $userAgent)) {
            $data['device_type'] = strpos($userAgent, 'iPad') !== false ? self::DEVICE_TABLET : self::DEVICE_MOBILE;
            $data['os'] = self::OS_IOS;
            $data['device_name'] = 'Apple ' . (strpos($userAgent, 'iPad') !== false ? 'iPad' : 'iPhone');
        } elseif (preg_match('/Android/i', $userAgent)) {
            $data['device_type'] = preg_match('/Tablet|SM-T/i', $userAgent) ? self::DEVICE_TABLET : self::DEVICE_MOBILE;
            $data['os'] = self::OS_ANDROID;
            $data['device_name'] = 'Android Device';
        } elseif (preg_match('/Windows/i', $userAgent)) {
            $data['os'] = self::OS_WINDOWS;
            $data['device_name'] = 'Windows PC';
        } elseif (preg_match('/Mac/i', $userAgent)) {
            $data['os'] = self::OS_MAC;
            $data['device_name'] = 'Apple Mac';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $data['os'] = self::OS_LINUX;
            $data['device_name'] = 'Linux PC';
        }
        
        // Detect browser
        if (preg_match('/Chrome/i', $userAgent) && !preg_match('/Edg/i', $userAgent)) {
            $data['browser'] = self::BROWSER_CHROME;
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            $data['browser'] = self::BROWSER_FIREFOX;
        } elseif (preg_match('/Safari/i', $userAgent) && !preg_match('/Chrome/i', $userAgent)) {
            $data['browser'] = self::BROWSER_SAFARI;
        } elseif (preg_match('/Edg/i', $userAgent)) {
            $data['browser'] = self::BROWSER_EDGE;
        } elseif (preg_match('/OPR/i', $userAgent)) {
            $data['browser'] = self::BROWSER_OPERA;
        }
        
        return $data;
    }

    public static function trackSession($userId, $sessionId, $isCurrent = false): self
    {
        $userAgent = request()->userAgent();
        $parsedAgent = self::parseUserAgent($userAgent);
        
        return self::updateOrCreate(
            ['session_id' => $sessionId],
            [
                'user_id' => $userId,
                'device_type' => $parsedAgent['device_type'],
                'browser' => $parsedAgent['browser'],
                'os' => $parsedAgent['os'],
                'device_name' => $parsedAgent['device_name'],
                'ip_address' => request()->ip(),
                'user_agent' => $userAgent,
                'is_current' => $isCurrent,
                'last_activity' => now(),
            ]
        );
    }

    public static function revokeOtherSessions($userId, $currentSessionId): void
    {
        self::where('user_id', $userId)
            ->where('session_id', '!=', $currentSessionId)
            ->update(['is_current' => false]);
    }

    public static function revokeSession($sessionId): void
    {
        $session = self::where('session_id', $sessionId)->first();
        
        if ($session) {
            $session->delete();
            
            // Also delete from Laravel's session table
            \DB::table('sessions')->where('id', $sessionId)->delete();
        }
    }

    public static function cleanExpiredSessions($days = 30): void
    {
        self::where('last_activity', '<', now()->subDays($days))->delete();
    }

    public function updateActivity(): void
    {
        $this->update(['last_activity' => now()]);
    }
}