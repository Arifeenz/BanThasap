@extends('layouts.public')

@section('title', 'ข่าวสาร - ตำบลท่าสาป')

@section('content')

<div class="bg-[#3a6b33] px-6 py-10 text-center pt-28">
    <h1 class="text-[#e8f5e3] text-2xl font-medium mb-2">{{ __('site.posts.header_title') }}</h1>
    <p class="text-[#b8d8b0] text-sm">{{ __('site.posts.header_subtitle') }}</p>
</div>

{{-- Filter Bar --}}
<div class="px-6 py-4 bg-white border-b border-[#d4e6cc] sticky z-40" style="top: var(--navbar-offset, 6.5rem); transition: top .3s ease;">
    {{-- แถว 1: หมวดหมู่ --}}
    <div class="flex flex-wrap gap-2 mb-3">
        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}"
           class="px-4 py-1.5 rounded-full text-sm transition-colors {{ !request('category') ? 'bg-[#2d5a27] text-white' : 'bg-[#f0f7ed] text-[#3a6b33] hover:bg-[#d4e6cc]' }}">
            {{ __('site.common.category.all') }}
        </a>
        <a href="{{ request()->fullUrlWithQuery(['category' => 'news']) }}"
           class="px-4 py-1.5 rounded-full text-sm transition-colors {{ request('category') === 'news' ? 'bg-[#2d5a27] text-white' : 'bg-[#f0f7ed] text-[#3a6b33] hover:bg-[#d4e6cc]' }}">
            {{ __('site.common.category.news') }}
        </a>
        <a href="{{ request()->fullUrlWithQuery(['category' => 'event']) }}"
           class="px-4 py-1.5 rounded-full text-sm transition-colors {{ request('category') === 'event' ? 'bg-[#2d5a27] text-white' : 'bg-[#f0f7ed] text-[#3a6b33] hover:bg-[#d4e6cc]' }}">
            {{ __('site.common.category.event') }}
        </a>
        <a href="{{ request()->fullUrlWithQuery(['category' => 'announcement']) }}"
           class="px-4 py-1.5 rounded-full text-sm transition-colors {{ request('category') === 'announcement' ? 'bg-[#2d5a27] text-white' : 'bg-[#f0f7ed] text-[#3a6b33] hover:bg-[#d4e6cc]' }}">
            {{ __('site.common.category.announcement') }}
        </a>
    </div>
    {{-- แถว 2: เดือน scroll --}}
    <div class="flex gap-2 overflow-x-auto pb-1">
        <a href="{{ request()->fullUrlWithQuery(['month' => null]) }}"
        class="flex-shrink-0 px-4 py-1.5 rounded-full text-sm transition-colors {{ !request('month') ? 'bg-[#3a6b33]/20 text-[#2d5a27] font-medium' : 'text-[#7a8c75] hover:text-[#2d5a27]' }}">
            {{ __('site.posts.all_months') }}
        </a>
        @foreach($months as $month)
        <a href="{{ request()->fullUrlWithQuery(['month' => $month->format('Y-m')]) }}"
        class="flex-shrink-0 px-4 py-1.5 rounded-full text-sm transition-colors {{ request('month') === $month->format('Y-m') ? 'bg-[#3a6b33]/20 text-[#2d5a27] font-medium' : 'text-[#7a8c75] hover:text-[#2d5a27]' }}">
            {{ $month->locale(app()->getLocale())->isoFormat('MMMM YYYY') }}
        </a>
        @endforeach
        @if($allMonthsCount > 12 && !request('show_all'))
        <a href="{{ request()->fullUrlWithQuery(['show_all' => 1]) }}"
        class="flex-shrink-0 px-4 py-1.5 rounded-full text-sm border border-[#3a6b33] text-[#3a6b33] hover:bg-[#f0f7ed] transition-colors">
            {{ __('site.posts.show_more_months') }}
        </a>
        @endif
    </div>
</div>

<div class="px-6 py-10">
    @if($posts->count() > 0)
    <div class="flex flex-col gap-4">
        @foreach($posts as $post)
        <a href="/posts/{{ $post->slug }}" class="bg-white border border-[#d4e6cc] rounded-xl p-4 flex gap-4 hover:border-[#6db85c] hover:shadow-md transition-all group">
            @if($post->image)
                <img src="{{ Storage::url($post->image) }}" loading="lazy" class="w-24 h-24 object-cover rounded-lg flex-shrink-0 group-hover:scale-105 transition-transform duration-300" alt="{{ $post->title }}">
            @else
                <div class="w-24 h-24 bg-[#f0f8ee] rounded-lg flex-shrink-0 flex items-center justify-center text-3xl">📰</div>
            @endif
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between mb-2 gap-2">
                    <h3 class="text-[#2d4a25] text-base font-medium group-hover:text-[#2d5a27] transition-colors">{{ $post->title }}</h3>
                    @if($post->category)
                        <span class="flex-shrink-0 text-xs px-2 py-0.5 rounded-full whitespace-nowrap
                            {{ $post->category === 'news' ? 'bg-[#e8f5e3] text-[#3a6b33]' : '' }}
                            {{ $post->category === 'event' ? 'bg-[#fef3e0] text-[#9a6b1a]' : '' }}
                            {{ $post->category === 'announcement' ? 'bg-[#fde8e8] text-[#9a1a1a]' : '' }}">
                            {{ match($post->category) {
                                'news', 'event', 'announcement' => __('site.common.category.' . $post->category),
                                default => $post->category,
                            } }}
                        </span>
                    @endif
                </div>
                <p class="text-[#7a8c75] text-sm mb-3 truncate">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                <p class="text-[#7a8c75] text-xs">{{ $post->published_at?->locale(app()->getLocale())->isoFormat('D MMMM YYYY') ?? $post->created_at->locale(app()->getLocale())->isoFormat('D MMMM YYYY') }}</p>
            </div>
        </a>
        @endforeach
    </div>
    <div class="mt-8">{{ $posts->links() }}</div>
    @else
    <div class="text-center py-20">
        <div class="text-5xl mb-4">📭</div>
        <p class="text-[#7a8c75]">{{ __('site.posts.empty_state') }}</p>
    </div>
    @endif
</div>

@endsection