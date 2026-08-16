@extends('layouts.public')

@section('title', '6 หมู่บ้าน - ตำบลท่าสาป')
@section('description', __('site.villages.header_subtitle') . ' รู้จัก 6 หมู่บ้านในตำบลท่าสาป อ.เมือง จ.ยะลา')

@section('content')

<div class="bg-[#3a6b33] px-6 py-10 text-center pt-28">
    <h1 class="text-[#e8f5e3] text-2xl font-medium mb-2">{{ __('site.villages.header_title') }}</h1>
    <p class="text-[#b8d8b0] text-sm">{{ __('site.villages.header_subtitle') }}</p>
</div>

<div class="px-6 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($villages as $village)
        <a href="/villages/{{ $village->slug }}" class="bg-white border border-[#d4e6cc] rounded-xl overflow-hidden hover:border-[#6db85c] hover:shadow-md transition-all group">
            <div class="h-40 bg-[#f0f8ee] flex items-center justify-center overflow-hidden">
                @if($village->image && Storage::disk('public')->exists($village->image))
                    <img src="{{ Storage::url($village->image) }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $village->name }}">
                @else
                    <span class="text-5xl">🏘️</span>
                @endif
            </div>
            <div class="p-5 flex gap-4">
                <div class="w-12 h-12 bg-[#3a6b33] rounded-full flex items-center justify-center text-white text-lg font-medium flex-shrink-0">{{ $village->number }}</div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-[#2d4a25] text-base font-medium mb-1">{{ $village->name }}</h3>
                    @if($village->highlight)
                        <p class="text-[#7a8c75] text-sm">{{ $village->highlight }}</p>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>

@endsection