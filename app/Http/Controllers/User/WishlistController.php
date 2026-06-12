<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $wishlists = Wishlist::where('user_id', $user->id)
            ->with('product.category')
            ->latest()
            ->paginate(12);
        
        $totalItems = $wishlists->total();
        
        return view('user.pages.wishlist', compact('wishlists', 'totalItems'));
    }

    public function add(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        
        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->exists();
        
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Produk sudah ada di wishlist Anda.',
            ], 409);
        }
        
        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
        ]);
        
        // Increment wishlist count on product
        Product::where('id', $request->product_id)->increment('wishlist_count');
        
        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke wishlist!',
            'wishlist_id' => $wishlist->id,
        ]);
    }

    public function remove($id)
    {
        $user = Auth::user();
        
        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();
        
        // Decrement wishlist count on product
        Product::where('id', $wishlist->product_id)->decrement('wishlist_count');
        
        $wishlist->delete();
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari wishlist!',
            ]);
        }
        
        return redirect()->route('user.wishlist')
            ->with('success', 'Produk berhasil dihapus dari wishlist!');
    }

    public function moveToCart($id)
    {
        $user = Auth::user();
        
        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();
        
        // Add to cart logic here
        // Cart::add($wishlist->product_id, 1);
        
        // Remove from wishlist
        Product::where('id', $wishlist->product_id)->decrement('wishlist_count');
        $wishlist->delete();
        
        return redirect()->route('user.wishlist')
            ->with('success', 'Produk dipindahkan ke keranjang!');
    }

    public function clear()
    {
        $user = Auth::user();
        
        // Decrement all wishlist counts
        $productIds = Wishlist::where('user_id', $user->id)->pluck('product_id');
        Product::whereIn('id', $productIds)->decrement('wishlist_count');
        
        Wishlist::where('user_id', $user->id)->delete();
        
        return redirect()->route('user.wishlist')
            ->with('success', 'Semua produk dihapus dari wishlist!');
    }

    public function check($productId)
    {
        $user = Auth::user();
        
        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();
        
        return response()->json([
            'in_wishlist' => $exists,
        ]);
    }

    public function bulkRemove(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:wishlists,id',
        ]);
        
        $wishlists = Wishlist::where('user_id', $user->id)
            ->whereIn('id', $request->ids)
            ->get();
        
        foreach ($wishlists as $wishlist) {
            Product::where('id', $wishlist->product_id)->decrement('wishlist_count');
            $wishlist->delete();
        }
        
        return redirect()->route('user.wishlist')
            ->with('success', count($request->ids) . ' produk berhasil dihapus!');
    }

    public function share()
    {
        $user = Auth::user();
        
        $wishlists = Wishlist::where('user_id', $user->id)
            ->with('product')
            ->get();
        
        // Generate shareable link
        $shareToken = base64_encode($user->id . '|' . now()->addDays(7)->timestamp);
        
        return response()->json([
            'share_url' => route('user.wishlist.shared', $shareToken),
            'products' => $wishlists->pluck('product'),
        ]);
    }

    public function shared($token)
    {
        // Decode token and validate
        $decoded = base64_decode($token);
        [$userId, $expiry] = explode('|', $decoded);
        
        if ($expiry < now()->timestamp) {
            abort(404, 'Link wishlist sudah kadaluarsa.');
        }
        
        $wishlists = Wishlist::where('user_id', $userId)
            ->with('product')
            ->get();
        
        return view('user.pages.wishlist-shared', compact('wishlists'));
    }
}