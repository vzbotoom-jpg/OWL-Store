<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'detail',
        'price', 'price_original', 'stock', 'weight', 'material',
        'finishing', 'size', 'production_days', 'is_custom',
        'is_featured', 'is_active', 'badge', 'sold_count',
        'wishlist_count', 'rating', 'review_count', 'image',
    ];

    protected $casts = [
        'is_custom'   => 'boolean',
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}