<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject')->nullable();
            $table->enum('status', [
                'active',      // Chat aktif
                'waiting',     // Menunggu respon admin
                'resolved',    // Selesai
                'closed'       // Ditutup oleh user/admin
            ])->default('active');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->integer('unread_count_user')->default(0);
            $table->integer('unread_count_admin')->default(0);
            $table->timestamp('last_message_at')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['admin_id', 'status']);
            $table->index(['status', 'updated_at']);
            $table->index('last_message_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};