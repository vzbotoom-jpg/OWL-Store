<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->string('attachment')->nullable(); // Path file attachment
            $table->string('attachment_type')->nullable(); // image, pdf, zip, etc
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_deleted_by_sender')->default(false);
            $table->boolean('is_deleted_by_receiver')->default(false);
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['chat_id', 'created_at']);
            $table->index(['sender_id', 'is_read']);
            $table->index(['receiver_id', 'is_read']);
            $table->index('created_at');
            
            // Composite index untuk pencarian chat
            $table->index(['chat_id', 'sender_id', 'is_deleted_by_sender']);
            $table->index(['chat_id', 'receiver_id', 'is_deleted_by_receiver']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};