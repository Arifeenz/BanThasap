@extends('layouts.public')

@section('title', $village->name . ' - ตำบลท่าสาป')

@section('content')

<div class="px-6 py-10 max-w-3xl mx-auto pt-20">
    <a href="/villages" class="text-[#3a6b33] text-sm mb-6 inline-block">{{ __('site.common.back') }}</a>

    <div class="bg-white border border-[#d4e6cc] rounded-xl overflow-hidden">
        @if($village->images->isNotEmpty())
            @php
                $coverImage = $village->images->firstWhere('is_cover', true) ?? $village->images->first();
            @endphp
            <img id="village-main-image" src="{{ Storage::url($coverImage->image) }}" class="w-full h-64 object-cover" alt="{{ $village->name }}">

            @if($village->images->count() > 1)
                <div class="flex gap-2 p-3 overflow-x-auto bg-white border-b border-[#d4e6cc]">
                    @foreach($village->images as $image)
                        <button
                            type="button"
                            onclick="document.getElementById('village-main-image').src = '{{ Storage::url($image->image) }}'"
                            class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 {{ $image->is($coverImage) ? 'border-[#3a6b33]' : 'border-transparent' }} hover:border-[#3a6b33] transition-colors"
                        >
                            <img src="{{ Storage::url($image->image) }}" loading="lazy" class="w-full h-full object-cover" alt="{{ $village->name }}">
                        </button>
                    @endforeach
                </div>
            @endif
        @endif
        <div class="p-6">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 bg-[#3a6b33] rounded-full flex items-center justify-center text-white text-xl font-medium flex-shrink-0">{{ $village->number }}</div>
                <div>
                    <h1 class="text-[#2d4a25] text-xl font-medium">{{ $village->name }}</h1>
                    @if($village->highlight)
                        <p class="text-[#7a8c75] text-sm">{{ $village->highlight }}</p>
                    @endif
                </div>
            </div>

            @if($village->description)
                <div class="prose prose-sm max-w-none text-[#4a6a45] mb-6">{!! $village->description !!}</div>
            @endif

            @if($village->attractions->count() > 0)
                <div class="border-t border-[#d4e6cc] pt-6">
                    <h2 class="text-[#2d5a27] text-base font-medium mb-4">{{ __('site.villages.attractions_in_village') }}</h2>
                    <div class="flex flex-col gap-3">
                        @foreach($village->attractions as $attraction)
                        <a href="/attractions/{{ $attraction->slug }}" class="flex items-center gap-3 p-3 border border-[#d4e6cc] rounded-xl hover:border-[#6db85c] transition-colors">
                            <div class="w-10 h-10 bg-[#f0f8ee] rounded-lg flex items-center justify-center text-xl flex-shrink-0">🏞️</div>
                            <div>
                                <h3 class="text-[#2d4a25] text-sm font-medium">{{ $attraction->name }}</h3>
                                @if($attraction->type)
                                    <span class="text-[#7a8c75] text-xs">
                                        {{ match($attraction->type) {
                                            'nature', 'history', 'learning', 'community' => __('site.common.type.' . $attraction->type),
                                            default => $attraction->type,
                                        } }}
                                    </span>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection