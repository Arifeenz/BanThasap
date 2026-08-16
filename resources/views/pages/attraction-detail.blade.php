@extends('layouts.public')

@section('title', $attraction->name . ' - ตำบลท่าสาป')

@section('content')

<div class="px-6 py-10 max-w-3xl mx-auto pt-20">
    <a href="/attractions" class="text-[#3a6b33] text-sm mb-6 inline-block">{{ __('site.common.back') }}</a>

    <div class="bg-white border border-[#d4e6cc] rounded-xl overflow-hidden">
        @if($attraction->images->isNotEmpty())
            @php
                $coverImage = $attraction->images->firstWhere('is_cover', true) ?? $attraction->images->first();
            @endphp
            <img id="attraction-main-image" src="{{ Storage::url($coverImage->image) }}" class="w-full object-contain max-h-[600px]" alt="{{ $attraction->name }}">

            @if($attraction->images->count() > 1)
                <div class="flex gap-2 p-3 overflow-x-auto bg-white border-b border-[#d4e6cc]">
                    @foreach($attraction->images as $image)
                        <button
                            type="button"
                            onclick="document.getElementById('attraction-main-image').src = '{{ Storage::url($image->image) }}'"
                            class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 {{ $image->is($coverImage) ? 'border-[#3a6b33]' : 'border-transparent' }} hover:border-[#3a6b33] transition-colors"
                        >
                            <img src="{{ Storage::url($image->image) }}" loading="lazy" class="w-full h-full object-cover" alt="{{ $attraction->name }}">
                        </button>
                    @endforeach
                </div>
            @endif
        @endif
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-[#2d4a25] text-xl font-medium mb-1">{{ $attraction->name }}</h1>
                    @if($attraction->village)
                        <p class="text-[#7a8c75] text-sm">{{ $attraction->village->name }}</p>
                    @endif
                </div>
                @if($attraction->type)
                    <span class="inline-block bg-[#e8f5e3] text-[#3a6b33] text-xs px-2 py-0.5 rounded-full">
                        {{ match($attraction->type) {
                            'nature', 'history', 'learning', 'community' => __('site.common.type.' . $attraction->type),
                            default => $attraction->type,
                        } }}
                    </span>
                @endif
            </div>

            @if($attraction->description)
                <div class="prose text-sm text-[#4a6a45] mb-6">{!! $attraction->description !!}</div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                @if($attraction->open_hours)
                    <div class="bg-[#f0f8ee] rounded-xl p-4">
                        <h2 class="text-[#2d5a27] text-sm font-medium mb-1">{{ __('site.attractions.open_hours') }}</h2>
                        <p class="text-[#4a6a45] text-sm">{{ $attraction->open_hours }}</p>
                    </div>
                @endif
                @if($attraction->contact)
                    @php
                        $isPhone = preg_match('/^[0-9\s\-+()]{9,}$/', trim($attraction->contact));
                        $phoneDigits = preg_replace('/[^0-9+]/', '', $attraction->contact);
                    @endphp
                    <div class="bg-[#f0f8ee] rounded-xl p-4">
                        <h2 class="text-[#2d5a27] text-sm font-medium mb-1">{{ __('site.common.contact') }}</h2>
                        @if($isPhone)
                            <a href="tel:{{ $phoneDigits }}" class="text-[#3a6b33] text-sm font-medium underline underline-offset-2">{{ $attraction->contact }}</a>
                        @else
                            <p class="text-[#4a6a45] text-sm">{{ $attraction->contact }}</p>
                        @endif
                    </div>
                @endif
            </div>

            @if($attraction->how_to_get)
                <div class="border border-[#d4e6cc] rounded-xl p-4 mb-6">
                    <h2 class="text-[#2d5a27] text-sm font-medium mb-2">{{ __('site.attractions.how_to_get') }}</h2>
                    <div class="prose text-sm text-[#4a6a45]">{!! $attraction->how_to_get !!}</div>
                </div>
            @endif

            @if($attraction->latitude && $attraction->longitude)
                <div class="border border-[#d4e6cc] rounded-xl overflow-hidden">
                    <div class="flex items-center justify-between p-4 pb-0">
                        <h2 class="text-[#2d5a27] text-sm font-medium">{{ __('site.attractions.map_position') }}</h2>
                        <a
                            href="https://www.google.com/maps/dir/?api=1&destination={{ $attraction->latitude }},{{ $attraction->longitude }}"
                            target="_blank"
                            rel="noopener"
                            class="bg-[#3a6b33] text-white text-xs px-3 py-1.5 rounded-lg hover:bg-[#2d5a27] transition-colors"
                        >{{ __('site.attractions.navigate_google') }}</a>
                    </div>
                    <div id="attraction-map" class="w-full h-64 mt-4"></div>
                </div>

                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    const map = L.map('attraction-map').setView([{{ $attraction->latitude }}, {{ $attraction->longitude }}], 16);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors',
                    }).addTo(map);

                    L.marker([{{ $attraction->latitude }}, {{ $attraction->longitude }}])
                        .addTo(map)
                        .bindPopup(@json($attraction->name));
                </script>
            @endif
        </div>
    </div>
</div>

@endsection