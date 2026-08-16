@extends('layouts.public')

@section('title', $post->title . ' - ตำบลท่าสาป')

@section('content')

<div class="px-6 py-10 max-w-3xl mx-auto pt-20">
    <a href="/posts" class="text-[#3a6b33] text-sm mb-6 inline-block">{{ __('site.common.back') }}</a>

    <div class="bg-white border border-[#d4e6cc] rounded-xl overflow-hidden">
        @if($post->images->isNotEmpty())
            @php
                $coverImage = $post->images->firstWhere('is_cover', true) ?? $post->images->first();
            @endphp
            <img id="post-main-image" src="{{ Storage::url($coverImage->image) }}" class="w-full object-contain max-h-[600px]" alt="{{ $post->title }}">

            @if($post->images->count() > 1)
                <div class="flex gap-2 p-3 overflow-x-auto bg-white border-b border-[#d4e6cc]">
                    @foreach($post->images as $image)
                        <button
                            type="button"
                            onclick="document.getElementById('post-main-image').src = '{{ Storage::url($image->image) }}'"
                            class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 {{ $image->is($coverImage) ? 'border-[#3a6b33]' : 'border-transparent' }} hover:border-[#3a6b33] transition-colors"
                        >
                            <img src="{{ Storage::url($image->image) }}" loading="lazy" class="w-full h-full object-cover" alt="{{ $post->title }}">
                        </button>
                    @endforeach
                </div>
            @endif
        @endif
        <div class="p-6">
            <div class="flex items-start justify-between mb-2">
                <h1 class="text-[#2d4a25] text-xl font-medium">{{ $post->title }}</h1>
                @if($post->category)
                    <span class="bg-[#fef3e0] text-[#9a6b1a] text-xs px-2 py-0.5 rounded-full whitespace-nowrap ml-2">
                        {{ match($post->category) {
                            'news', 'event', 'announcement' => __('site.common.category.' . $post->category),
                            default => $post->category,
                        } }}
                    </span>
                @endif
            </div>
            <p class="text-[#7a8c75] text-xs mb-6">{{ $post->published_at?->locale(app()->getLocale())->isoFormat('D MMMM YYYY') ?? $post->created_at->locale(app()->getLocale())->isoFormat('D MMMM YYYY') }}</p>
            <div class="prose prose-sm max-w-none text-[#4a6a45]">{!! $post->content !!}</div>
        </div>
    </div>
</div>

@endsection