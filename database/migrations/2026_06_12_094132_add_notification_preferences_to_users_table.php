<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Email Notifications
            $table->boolean('notify_email_order_status')->default(true)->after('remember_token');
            $table->boolean('notify_email_promo')->default(true)->after('notify_email_order_status');
            $table->boolean('notify_email_newsletter')->default(false)->after('notify_email_promo');
            $table->boolean('notify_email_marketing')->default(false)->after('notify_email_newsletter');
            
            // WhatsApp Notifications
            $table->boolean('notify_whatsapp_order_status')->default(true)->after('notify_email_marketing');
            $table->boolean('notify_whatsapp_promo')->default(true)->after('notify_whatsapp_order_status');
            $table->boolean('notify_whatsapp_marketing')->default(false)->after('notify_whatsapp_promo');
            
            // SMS Notifications
            $table->boolean('notify_sms_order_status')->default(false)->after('notify_whatsapp_marketing');
            $table->boolean('notify_sms_promo')->default(false)->after('notify_sms_order_status');
            
            // Push Notifications (Browser)
            $table->boolean('notify_push_order_status')->default(true)->after('notify_sms_promo');
            $table->boolean('notify_push_promo')->default(true)->after('notify_push_order_status');
            
            // In-App Notifications
            $table->boolean('notify_inapp_chat')->default(true)->after('notify_push_promo');
            $table->boolean('notify_inapp_order')->default(true)->after('notify_inapp_chat');
            $table->boolean('notify_inapp_promo')->default(true)->after('notify_inapp_order');
            
            // Notification schedule preferences
            $table->string('notification_schedule')->default('realtime')->after('notify_inapp_promo'); // realtime, daily, weekly
            $table->time('notification_daily_time')->default('09:00')->after('notification_schedule');
            
            // Do Not Disturb settings
            $table->boolean('dnd_enabled')->default(false)->after('notification_daily_time');
            $table->time('dnd_start_time')->default('22:00')->after('dnd_enabled');
            $table->time('dnd_end_time')->default('08:00')->after('dnd_start_time');
            
            // Indexes untuk performa query notifikasi
            $table->index('notify_email_order_status');
            $table->index('notify_whatsapp_order_status');
            $table->index('notify_push_order_status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'notify_email_order_status',
                'notify_email_promo',
                'notify_email_newsletter',
                'notify_email_marketing',
                'notify_whatsapp_order_status',
                'notify_whatsapp_promo',
                'notify_whatsapp_marketing',
                'notify_sms_order_status',
                'notify_sms_promo',
                'notify_push_order_status',
                'notify_push_promo',
                'notify_inapp_chat',
                'notify_inapp_order',
                'notify_inapp_promo',
                'notification_schedule',
                'notification_daily_time',
                'dnd_enabled',
                'dnd_start_time',
                'dnd_end_time',
            ]);
        });
    }
};