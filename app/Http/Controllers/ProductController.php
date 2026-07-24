<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::where('is_active', true);

        if (request('category')) {
            $query->where('category', request('category'));
        }

        return view('pages.products', [
            'products' => $query->latest()->paginate(9)->withQueryString(),
        ]);
    }

    public function show($slug)
    {
        $product = Product::with('images')->where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('pages.product-detail', compact('product'));
    }
}