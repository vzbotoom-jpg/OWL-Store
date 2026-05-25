<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->integer('total');
        $table->enum('status', ['pending','processed','shipped','completed','cancelled'])->default('pending');
        $table->string('payment_method')->nullable();
        $table->enum('payment_status', ['unpaid','paid','failed'])->default('unpaid');
        $table->text('shipping_address')->nullable();
        $table->text('notes')->nullable();
        $table->string('resi')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
