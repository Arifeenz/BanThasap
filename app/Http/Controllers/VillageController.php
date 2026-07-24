<?php

namespace App\Http\Controllers;

use App\Models\Village;

class VillageController extends Controller
{
    public function index()
    {
        return view('pages.villages', [
            'villages' => Village::where('is_active', true)->orderBy('number')->get(),
        ]);
    }

    public function show($slug)
    {
        $village = Village::where('slug', $slug)->where('is_active', true)->with('attractions')->firstOrFail();
        return view('pages.village-detail', compact('village'));
    }
}