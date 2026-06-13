<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'image' => 'nullable|image|max:2048',
        ]);

        // Check if user already reviewed this product
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        $data = [
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('reviews', 'public');
        }

        Review::create($data);

        // Update product rating
        $this->updateProductRating($request->product_id);

        return redirect()->back()->with('success', 'Terima kasih! Ulasan Anda telah disimpan.');
    }

    /**
     * Reply to a review (Admin only)
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|min:5|max:500',
        ]);

        $review = Review::findOrFail($id);
        $review->update(['reply' => $request->reply]);

        return response()->json(['success' => true, 'message' => 'Balasan berhasil dikirim']);
    }

    /**
     * Delete a review
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        // Check authorization (only admin or review owner can delete)
        if (Auth::user()->is_admin || Auth::id() === $review->user_id) {
            if ($review->image) {
                Storage::disk('public')->delete($review->image);
            }
            $review->delete();
            $this->updateProductRating($review->product_id);
            
            return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus ulasan ini.');
    }

    /**
     * Update product rating based on all reviews
     */
    private function updateProductRating($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $avgRating = Review::where('product_id', $productId)
                ->where('is_approved', true)
                ->avg('rating') ?? 5;
            
            $reviewCount = Review::where('product_id', $productId)
                ->where('is_approved', true)
                ->count();
            
            $product->update([
                'rating' => round($avgRating, 1),
                'review_count' => $reviewCount,
            ]);
        }
    }
}