<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('detail')->nullable();
            $table->integer('price');
            $table->integer('price_original')->nullable();
            $table->integer('stock')->default(0);
            $table->string('weight')->nullable();
            $table->string('material')->nullable();
            $table->string('finishing')->nullable();
            $table->string('size')->nullable();
            $table->string('production_days')->nullable();
            $table->boolean('is_custom')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('badge')->nullable();
            $table->integer('sold_count')->default(0);
            $table->integer('wishlist_count')->default(0);
            $table->decimal('rating', 3, 1)->default(5.0);
            $table->integer('review_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};