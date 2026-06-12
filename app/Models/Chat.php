<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    protected $table = 'chats';
    
    protected $fillable = [
        'user_id',
        'admin_id',
        'subject',
        'status',
        'priority',
        'unread_count_user',
        'unread_count_admin',
        'last_message_at',
        'assigned_at',
        'resolved_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'assigned_at' => 'datetime',
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== STATUS CONSTANTS ==========
    
    const STATUS_ACTIVE = 'active';
    const STATUS_WAITING = 'waiting';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';

    // ========== RELATIONSHIPS ==========
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    public function lastMessage(): HasMany
    {
        return $this->hasOne(ChatMessage::class)->latest();
    }

    // ========== SCOPES ==========
    
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_WAITING]);
    }

    public function scopeResolved($query)
    {
        return $query->where('status', self::STATUS_RESOLVED);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('admin_id');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeOldestUnread($query)
    {
        return $query->orderBy('last_message_at', 'asc');
    }

    // ========== ACCESSORS ==========
    
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_WAITING => 'Menunggu Respon',
            self::STATUS_RESOLVED => 'Selesai',
            self::STATUS_CLOSED => 'Ditutup',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'green',
            self::STATUS_WAITING => 'yellow',
            self::STATUS_RESOLVED => 'blue',
            self::STATUS_CLOSED => 'gray',
            default => 'gray',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'Rendah',
            self::PRIORITY_MEDIUM => 'Sedang',
            self::PRIORITY_HIGH => 'Tinggi',
            default => ucfirst($this->priority),
        };
    }

    // ========== HELPERS ==========
    
    public function markAsResolved(): void
    {
        $this->update([
            'status' => self::STATUS_RESOLVED,
            'resolved_at' => now(),
        ]);
    }

    public function markAsClosed(): void
    {
        $this->update(['status' => self::STATUS_CLOSED]);
    }

    public function reopen(): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'resolved_at' => null,
        ]);
    }

    public function assignToAdmin($adminId): void
    {
        $this->update([
            'admin_id' => $adminId,
            'assigned_at' => now(),
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function incrementUnreadCount($isAdmin): void
    {
        if ($isAdmin) {
            $this->increment('unread_count_admin');
        } else {
            $this->increment('unread_count_user');
        }
    }

    public function resetUnreadCount($isAdmin): void
    {
        if ($isAdmin) {
            $this->update(['unread_count_admin' => 0]);
        } else {
            $this->update(['unread_count_user' => 0]);
        }
    }

    public function sendMessage($senderId, $receiverId, $message, $attachment = null): ChatMessage
    {
        $chatMessage = $this->messages()->create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message,
            'attachment' => $attachment,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->update(['last_message_at' => now()]);
        
        // Increment unread count for receiver
        $isAdminReceiver = $receiverId !== $this->user_id;
        $this->incrementUnreadCount($isAdminReceiver);

        return $chatMessage;
    }
}