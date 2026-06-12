<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Two Factor Authentication
            $table->boolean('two_factor_enabled')->default(false)->after('remember_token');
            $table->string('two_factor_secret')->nullable()->after('two_factor_enabled');
            $table->string('two_factor_phone')->nullable()->after('two_factor_secret');
            $table->boolean('two_factor_verified')->default(false)->after('two_factor_phone');
            $table->timestamp('two_factor_verified_at')->nullable()->after('two_factor_verified');
            
            // Backup codes for 2FA recovery
            $table->text('two_factor_backup_codes')->nullable()->after('two_factor_verified_at');
            
            // Last login tracking
            $table->timestamp('last_login_at')->nullable()->after('two_factor_backup_codes');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->string('last_login_device')->nullable()->after('last_login_ip');
            
            // Account status
            $table->boolean('is_active')->default(true)->after('last_login_device');
            $table->timestamp('account_blocked_until')->nullable()->after('is_active');
            $table->integer('login_attempts')->default(0)->after('account_blocked_until');
            $table->timestamp('locked_until')->nullable()->after('login_attempts');
            
            // Indexes
            $table->index('two_factor_enabled');
            $table->index('is_active');
            $table->index('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_enabled',
                'two_factor_secret',
                'two_factor_phone',
                'two_factor_verified',
                'two_factor_verified_at',
                'two_factor_backup_codes',
                'last_login_at',
                'last_login_ip',
                'last_login_device',
                'is_active',
                'account_blocked_until',
                'login_attempts',
                'locked_until',
            ]);
        });
    }
};