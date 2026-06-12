<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $table = 'chat_messages';
    
    protected $fillable = [
        'chat_id',
        'sender_id',
        'receiver_id',
        'message',
        'attachment',
        'attachment_type',
        'is_read',
        'read_at',
        'is_deleted_by_sender',
        'is_deleted_by_receiver',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_deleted_by_sender' => 'boolean',
        'is_deleted_by_receiver' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== ATTACHMENT TYPES ==========
    
    const ATTACHMENT_IMAGE = 'image';
    const ATTACHMENT_PDF = 'pdf';
    const ATTACHMENT_ZIP = 'zip';
    const ATTACHMENT_DOCUMENT = 'document';

    // ========== RELATIONSHIPS ==========
    
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // ========== SCOPES ==========
    
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('sender_id', $userId)
                ->orWhere('receiver_id', $userId);
        });
    }

    public function scopeNotDeletedBy($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('sender_id', '!=', $userId)
                ->orWhere('is_deleted_by_sender', false);
        })->where(function($q) use ($userId) {
            $q->where('receiver_id', '!=', $userId)
                ->orWhere('is_deleted_by_receiver', false);
        });
    }

    // ========== ACCESSORS ==========
    
    public function getAttachmentUrlAttribute(): ?string
    {
        if ($this->attachment) {
            return asset('storage/' . $this->attachment);
        }
        return null;
    }

    public function getAttachmentIconAttribute(): string
    {
        return match($this->attachment_type) {
            self::ATTACHMENT_IMAGE => 'ti-photo',
            self::ATTACHMENT_PDF => 'ti-file-pdf',
            self::ATTACHMENT_ZIP => 'ti-file-zip',
            default => 'ti-file',
        };
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->format('H:i');
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d M Y');
    }

    // ========== HELPERS ==========
    
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function isSender($userId): bool
    {
        return $this->sender_id === $userId;
    }

    public function isReceiver($userId): bool
    {
        return $this->receiver_id === $userId;
    }

    public function deleteForUser($userId): void
    {
        if ($this->sender_id === $userId) {
            $this->update(['is_deleted_by_sender' => true]);
        } elseif ($this->receiver_id === $userId) {
            $this->update(['is_deleted_by_receiver' => true]);
        }

        // Delete permanently if both users deleted
        if ($this->is_deleted_by_sender && $this->is_deleted_by_receiver) {
            $this->forceDelete();
        }
    }

    public static function getAttachmentType($filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        return match($extension) {
            'jpg', 'jpeg', 'png', 'gif', 'webp' => self::ATTACHMENT_IMAGE,
            'pdf' => self::ATTACHMENT_PDF,
            'zip', 'rar', '7z' => self::ATTACHMENT_ZIP,
            'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx' => self::ATTACHMENT_DOCUMENT,
            default => 'file',
        };
    }
}