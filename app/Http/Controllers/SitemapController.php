<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use App\Models\Post;
use App\Models\Product;
use App\Models\Village;

class SitemapController extends Controller
{
    public function index()
    {
        $staticUrls = [
            ['url' => url('/'), 'priority' => '1.0'],
            ['url' => url('/products'), 'priority' => '0.8'],
            ['url' => url('/attractions'), 'priority' => '0.8'],
            ['url' => url('/posts'), 'priority' => '0.7'],
            ['url' => url('/villages'), 'priority' => '0.7'],
            ['url' => url('/map'), 'priority' => '0.6'],
        ];

        $products = Product::where('is_active', true)->get(['slug', 'updated_at']);
        $attractions = Attraction::where('is_active', true)->get(['slug', 'updated_at']);
        $posts = Post::where('is_published', true)->get(['slug', 'updated_at']);
        $villages = Village::where('is_active', true)->get(['slug', 'updated_at']);

        return response()
            ->view('sitemap', compact('staticUrls', 'products', 'attractions', 'posts', 'villages'))
            ->header('Content-Type', 'application/xml');
    }
}
