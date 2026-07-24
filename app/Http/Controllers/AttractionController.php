<?php

namespace App\Http\Controllers;

use App\Models\Attraction;

class AttractionController extends Controller
{
    public function index()
    {
        return view('pages.attractions', [
            'attractions' => Attraction::where('is_active', true)->with('village')->latest()->paginate(9)->withQueryString(),
        ]);
    }

    public function show($slug)
    {
        $attraction = Attraction::where('slug', $slug)->where('is_active', true)->with('village')->firstOrFail();
        return view('pages.attraction-detail', compact('attraction'));
    }
}