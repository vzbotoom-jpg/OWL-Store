<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hanya tambahkan kolom yang BELUM ADA
            
            // Kolom shipping_city
            if (!Schema::hasColumn('orders', 'shipping_city')) {
                $table->string('shipping_city')->nullable()->after('shipping_address');
            }
            
            // Kolom shipping_province
            if (!Schema::hasColumn('orders', 'shipping_province')) {
                $table->string('shipping_province')->nullable()->after('shipping_city');
            }
            
            // Kolom shipping_postal_code
            if (!Schema::hasColumn('orders', 'shipping_postal_code')) {
                $table->string('shipping_postal_code')->nullable()->after('shipping_province');
            }
            
            // Kolom shipping_courier
            if (!Schema::hasColumn('orders', 'shipping_courier')) {
                $table->string('shipping_courier')->nullable()->after('shipping_postal_code');
            }
            
            // Kolom coupon_code
            if (!Schema::hasColumn('orders', 'coupon_code')) {
                $table->string('coupon_code')->nullable()->after('notes');
            }
            
            // Kolom coupon_id
            if (!Schema::hasColumn('orders', 'coupon_id')) {
                $table->foreignId('coupon_id')->nullable()->after('coupon_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_city',
                'shipping_province',
                'shipping_postal_code',
                'shipping_courier',
                'coupon_code',
                'coupon_id'
            ]);
        });
    }
};