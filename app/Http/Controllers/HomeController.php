<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use App\Models\HeroSlide;
use App\Models\Post;
use App\Models\Product;
use App\Models\Village;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home', [
            'heroSlides' => HeroSlide::where('is_active', true)->orderBy('order')->get(),
            'products' => $this->pickHighlighted(Product::where('is_active', true)),
            'posts' => Post::where('is_published', true)->latest()->take(3)->get(),
            'villages' => Village::where('is_active', true)->orderBy('number')->get(),
            'attractions' => $this->pickHighlighted(Attraction::where('is_active', true)->with('village')),
            'productCount' => Product::where('is_active', true)->count(),
            'attractionCount' => Attraction::where('is_active', true)->count(),
            'mapAttractions' => Attraction::where('is_active', true)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get(),
            'mapVillages' => Village::where('is_active', true)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->with(['products' => fn ($query) => $query->where('is_active', true)])
                ->get(),
        ]);
    }

    /**
     * เลือกรายการที่ปักหมุด (is_featured) มาก่อน แล้วสุ่มเติมที่เหลือให้ครบ 3
     * เพื่อไม่ให้สินค้า/สถานที่ที่เพิ่งอัปโหลดล่าสุดได้เปรียบขึ้นหน้าแรกเสมอ
     */
    private function pickHighlighted($query, int $limit = 3)
    {
        $featured = (clone $query)->where('is_featured', true)->inRandomOrder()->take($limit)->get();

        $remaining = $limit - $featured->count();

        if ($remaining <= 0) {
            return $featured;
        }

        $random = (clone $query)
            ->where('is_featured', false)
            ->inRandomOrder()
            ->take($remaining)
            ->get();

        return $featured->concat($random);
    }
}