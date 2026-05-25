<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.products.index');
    }

    public function show(Product $product)
    {
        return view('pages.products.show', compact('product'));
    }

    public function category(Category $category)
    {
        return view('pages.products.index', compact('category'));
    }
}