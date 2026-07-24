<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use App\Models\Village;

class MapController extends Controller
{
    public function index()
    {
        return view('pages.map', [
            'attractions' => Attraction::where('is_active', true)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->with('village')
                ->get(),
            'villages' => Village::where('is_active', true)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->with(['products' => fn ($query) => $query->where('is_active', true)])
                ->get(),
        ]);
    }
}
