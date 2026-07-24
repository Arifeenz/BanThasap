<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Carbon;

class PostController extends Controller
{
    public function index()
    {
        $query = Post::where('is_published', true);

        // Filter category
        if (request('category')) {
            $query->where('category', request('category'));
        }

        // Filter month
        if (request('month')) {
            try {
                $month = Carbon::parse(request('month'));
                $query->whereYear('published_at', $month->year)
                      ->whereMonth('published_at', $month->month);
            } catch (\Exception $e) {
                // ค่า month ในลิงก์ผิดรูปแบบ ไม่กรองตามเดือน
            }
        }

        // ดึงเดือนทั้งหมดที่มีข่าว
        $months = Post::where('is_published', true)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->pluck('published_at')
            ->map(fn($date) => Carbon::parse($date)->startOfMonth())
            ->unique()
            ->values();

        $allMonthsCount = $months->count();
        if (!request('show_all')) {
            $months = $months->take(12);
        }

        return view('pages.posts', [
            'posts' => $query->latest('published_at')->paginate(9)->withQueryString(),
            'months' => $months,
            'allMonthsCount' => $allMonthsCount,
        ]);
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->where('is_published', true)->firstOrFail();
        return view('pages.post-detail', compact('post'));
    }
}