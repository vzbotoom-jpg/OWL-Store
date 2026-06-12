<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);
        
        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Filter by stock
        if ($request->has('in_stock') && $request->in_stock) {
            $query->where('stock', '>', 0);
        }
        
        // Sort
        switch ($request->get('sort', 'latest')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('sold_count', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->latest();
                break;
        }
        
        $products = $query->paginate(12);
        
        // For AJAX requests
        if ($request->ajax()) {
            $html = view('pages.products._product_grid', compact('products'))->render();
            $pagination = $products->links()->render();
            
            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ]);
        }
        
        return view('pages.products.index', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::with('category', 'reviews.user')
            ->where('slug', $slug)
            ->firstOrFail();
        
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();
        
        return view('pages.products.show', compact('product', 'relatedProducts'));
    }

    public function category($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $query = Product::with('category')
            ->where('category_id', $category->id)
            ->where('is_active', true);
        
        // Similar filters as above
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        
        $products = $query->paginate(12);
        
        if ($request->ajax()) {
            $html = view('pages.products._product_grid', compact('products'))->render();
            $pagination = $products->links()->render();
            
            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ]);
        }
        
        return view('pages.products.index', compact('products', 'category'));
    }
}