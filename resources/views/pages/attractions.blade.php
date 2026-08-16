@extends('layouts.public')

@section('title', 'ท่องเที่ยว - ตำบลท่าสาป')

@section('content')

<div class="bg-[#3a6b33] px-6 py-10 text-center pt-28">
    <h1 class="text-[#e8f5e3] text-2xl font-medium mb-2">{{ __('site.attractions.header_title') }}</h1>
    <p class="text-[#b8d8b0] text-sm">{{ __('site.attractions.header_subtitle') }}</p>
</div>

<div class="px-6 py-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @foreach($attractions as $attraction)
        <a href="/attractions/{{ $attraction->slug }}" class="bg-white border border-[#d4e6cc] rounded-xl overflow-hidden block hover:border-[#6db85c] transition-colors">
            <div class="h-40 bg-[#f0f8ee] flex items-center justify-center">
                @if($attraction->image)
                    <img src="{{ Storage::url($attraction->image) }}" loading="lazy" class="w-full h-full object-cover" alt="{{ $attraction->name }}">
                @else
                    <span class="text-5xl">🏞️</span>
                @endif
            </div>
            <div class="p-4">
                <h3 class="text-[#2d4a25] text-sm font-medium mb-1">{{ $attraction->name }}</h3>
                @if($attraction->village)
                    <p class="text-[#7a8c75] text-xs mb-2">{{ $attraction->village->name }}</p>
                @endif
                @if($attraction->type)
                    <span class="inline-block bg-[#e8f5e3] text-[#3a6b33] text-xs px-2 py-0.5 rounded-full">
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
    <div class="mt-8">{{ $attractions->links() }}</div>
</div>

@endsection