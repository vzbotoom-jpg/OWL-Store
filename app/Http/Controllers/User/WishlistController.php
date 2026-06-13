<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Toggle wishlist (add/remove)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        // Check if product is already in wishlist
        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            // Remove from wishlist
            $wishlist->delete();
            
            // Decrement wishlist count on product
            Product::where('id', $productId)->decrement('wishlist_count');
            
            return response()->json([
                'success' => true,
                'in_wishlist' => false,
                'message' => 'Produk dihapus dari wishlist'
            ]);
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            
            // Increment wishlist count on product
            Product::where('id', $productId)->increment('wishlist_count');
            
            return response()->json([
                'success' => true,
                'in_wishlist' => true,
                'message' => 'Produk ditambahkan ke wishlist'
            ]);
        }
    }

    /**
     * Display wishlist page
     */
    public function index()
    {
        $user = Auth::user();
        $wishlists = Wishlist::where('user_id', $user->id)
            ->with('product')
            ->latest()
            ->paginate(12);
        
        $totalItems = $wishlists->total();
        
        return view('user.pages.wishlist', compact('wishlists', 'totalItems'));
    }

    /**
     * Add to wishlist (alternative method)
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Produk sudah ada di wishlist'
            ]);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id
        ]);

        Product::where('id', $request->product_id)->increment('wishlist_count');

        return response()->json([
            'success' => true,
            'message' => 'Produk ditambahkan ke wishlist'
        ]);
    }

    /**
     * Remove from wishlist
     */
    public function remove($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        Product::where('id', $wishlist->product_id)->decrement('wishlist_count');
        $wishlist->delete();

        return redirect()->route('user.wishlist')
            ->with('success', 'Produk dihapus dari wishlist');
    }

    /**
     * Move wishlist item to cart
     */
    public function moveToCart($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        // Add to cart logic here
        // Cart::add($wishlist->product_id, 1);

        Product::where('id', $wishlist->product_id)->decrement('wishlist_count');
        $wishlist->delete();

        return redirect()->route('user.wishlist')
            ->with('success', 'Produk dipindahkan ke keranjang');
    }

    /**
     * Clear all wishlist
     */
    public function clear()
    {
        $user = Auth::user();
        
        $productIds = Wishlist::where('user_id', $user->id)->pluck('product_id');
        Product::whereIn('id', $productIds)->decrement('wishlist_count');
        
        Wishlist::where('user_id', $user->id)->delete();

        return redirect()->route('user.wishlist')
            ->with('success', 'Wishlist berhasil dikosongkan');
    }

    /**
     * Bulk remove from wishlist
     */
    public function bulkRemove(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:wishlists,id'
        ]);

        $wishlists = Wishlist::where('user_id', Auth::id())
            ->whereIn('id', $request->ids)
            ->get();

        foreach ($wishlists as $wishlist) {
            Product::where('id', $wishlist->product_id)->decrement('wishlist_count');
            $wishlist->delete();
        }

        return redirect()->route('user.wishlist')
            ->with('success', count($request->ids) . ' produk dihapus dari wishlist');
    }

    /**
     * Check if product is in wishlist
     */
    public function check($productId)
    {
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        return response()->json([
            'in_wishlist' => $exists
        ]);
    }
}