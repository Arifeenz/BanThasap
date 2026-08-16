@extends('layouts.public')

@section('title', 'หน้าแรก - ตำบลท่าสาป')

@section('content')

{{-- Hero Slideshow --}}
<div class="relative min-h-screen overflow-hidden" id="hero-slider">

    {{-- Slides --}}
    @foreach($heroSlides as $index => $slide)
    <div class="hero-slide absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}">
        <img src="{{ Storage::url($slide->image) }}" @if($index > 0) loading="lazy" @endif class="w-full h-full object-cover" alt="{{ $slide->title }}">
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/30 to-black/60"></div>
        
        @if($slide->title || $slide->subtitle)
        <div class="absolute inset-0 flex items-center justify-center z-10">
            <div class="text-center px-4">
                @if($slide->title)
                    <h1 class="text-white text-3xl md:text-5xl font-semibold mb-4 drop-shadow-lg">{{ $slide->title }}</h1>
                @endif
                @if($slide->subtitle)
                    <p class="text-white/90 text-base md:text-xl drop-shadow">{{ $slide->subtitle }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>
    @endforeach

    {{-- Overlay Content --}}
    <div class="absolute inset-0 z-20 flex flex-col items-center justify-end pb-16 px-4">
        <span class="inline-block bg-white/20 text-white text-xs px-4 py-1.5 rounded-full mb-4 border border-white/30 backdrop-blur-sm">{{ __('site.home.hero_badge') }}</span>
        <div class="flex gap-3 justify-center flex-wrap mb-8">
            <a href="/villages" class="bg-white text-[#2d5a27] px-6 py-3 rounded-xl text-sm font-medium hover:bg-[#a8d5a0] transition-colors">{{ __('site.home.explore_community') }}</a>
            <a href="/products" class="bg-transparent border-2 border-white/60 text-white px-6 py-3 rounded-xl text-sm font-medium hover:bg-white/10 transition-colors">{{ __('site.home.community_products') }}</a>
        </div>

        {{-- Dot Indicators --}}
        <div class="flex gap-2 mb-6">
            @foreach($heroSlides as $index => $slide)
            <button class="hero-dot w-2.5 h-2.5 rounded-full transition-all {{ $index === 0 ? 'bg-white w-6' : 'bg-white/50' }}" data-index="{{ $index }}"></button>
            @endforeach
        </div>
    </div>

    {{-- Prev/Next Buttons --}}
    <button id="hero-prev" class="absolute left-4 top-1/2 -translate-y-1/2 z-30 bg-white/20 hover:bg-white/40 text-white w-10 h-10 rounded-full flex items-center justify-center backdrop-blur-sm transition-colors">
        ‹
    </button>
    <button id="hero-next" class="absolute right-4 top-1/2 -translate-y-1/2 z-30 bg-white/20 hover:bg-white/40 text-white w-10 h-10 rounded-full flex items-center justify-center backdrop-blur-sm transition-colors">
        ›
    </button>

    {{-- Stats Bar --}}
    <div class="absolute bottom-0 left-0 right-0 z-30 bg-black/30 backdrop-blur-sm">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 px-6 py-4">
            <div class="text-center">
                <div class="text-white text-xl md:text-2xl font-semibold">6</div>
                <div class="text-white/70 text-xs mt-0.5">{{ __('site.home.stat_villages') }}</div>
            </div>
            <div class="text-center">
                <div class="text-white text-xl md:text-2xl font-semibold">{{ $productCount }}</div>
                <div class="text-white/70 text-xs mt-0.5">{{ __('site.home.stat_products') }}</div>
            </div>
            <div class="text-center">
                <div class="text-white text-xl md:text-2xl font-semibold">{{ $attractionCount }}</div>
                <div class="text-white/70 text-xs mt-0.5">{{ __('site.home.stat_attractions') }}</div>
            </div>
            <div class="text-center">
                <div class="text-white text-xl md:text-2xl font-semibold">2</div>
                <div class="text-white/70 text-xs mt-0.5">{{ __('site.home.stat_culture') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Hero Slideshow Script --}}
<script>
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    const slideDurations = @json($heroSlides->pluck('duration')->toArray());
    let current = 0;
    let timer;

    function goTo(index) {
        slides[current].classList.remove('opacity-100', 'z-10');
        slides[current].classList.add('opacity-0', 'z-0');
        dots[current].classList.remove('bg-white', 'w-6');
        dots[current].classList.add('bg-white/50');

        current = (index + slides.length) % slides.length;

        slides[current].classList.remove('opacity-0', 'z-0');
        slides[current].classList.add('opacity-100', 'z-10');
        dots[current].classList.remove('bg-white/50');
        dots[current].classList.add('bg-white', 'w-6');
    }

    function startTimer() {
        const duration = slideDurations[current] || 5;
        timer = setTimeout(() => {
            goTo(current + 1);
            startTimer();
        }, duration * 1000);
    }

    function resetTimer() {
        clearTimeout(timer);
        startTimer();
    }

    document.getElementById('hero-next').addEventListener('click', () => { goTo(current + 1); resetTimer(); });
    document.getElementById('hero-prev').addEventListener('click', () => { goTo(current - 1); resetTimer(); });
    dots.forEach(dot => dot.addEventListener('click', () => { goTo(parseInt(dot.dataset.index)); resetTimer(); }));

    startTimer();
</script>

{{-- อัตลักษณ์ชุมชน --}}
<div class="bg-[#f0f7ed] px-4 md:px-6 py-10">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-[#2d5a27] text-2xl md:text-3xl font-semibold mb-2">{{ __('site.home.identity_title') }}</h2>
            <p class="text-[#7a8c75] text-sm">{{ __('site.home.identity_subtitle') }}</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="text-center group">
                <div class="w-16 h-16 bg-[#2d5a27]/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl group-hover:bg-[#2d5a27]/20 transition-colors">👥</div>
                <h3 class="text-[#2d5a27] text-sm font-medium mb-2">{{ __('site.home.multicultural_title') }}</h3>
                <p class="text-[#7a8c75] text-xs leading-relaxed">{{ __('site.home.multicultural_desc') }}</p>
            </div>
            <div class="text-center group">
                <div class="w-16 h-16 bg-[#2d5a27]/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl group-hover:bg-[#2d5a27]/20 transition-colors">🌊</div>
                <h3 class="text-[#2d5a27] text-sm font-medium mb-2">{{ __('site.home.river_title') }}</h3>
                <p class="text-[#7a8c75] text-xs leading-relaxed">{{ __('site.home.river_desc') }}</p>
            </div>
            <div class="text-center group">
                <div class="w-16 h-16 bg-[#2d5a27]/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl group-hover:bg-[#2d5a27]/20 transition-colors">🌳</div>
                <h3 class="text-[#2d5a27] text-sm font-medium mb-2">{{ __('site.home.forest_title') }}</h3>
                <p class="text-[#7a8c75] text-xs leading-relaxed">{{ __('site.home.forest_desc') }}</p>
            </div>
            <div class="text-center group">
                <div class="w-16 h-16 bg-[#2d5a27]/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl group-hover:bg-[#2d5a27]/20 transition-colors">🏛️</div>
                <h3 class="text-[#2d5a27] text-sm font-medium mb-2">{{ __('site.home.old_town_title') }}</h3>
                <p class="text-[#7a8c75] text-xs leading-relaxed">{{ __('site.home.old_town_desc') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- สถานที่ท่องเที่ยว --}}
<div class="py-0">
    @if($attractions->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 min-h-[500px]">
        
        {{-- รูปใหญ่ซ้าย --}}
        <div class="relative overflow-hidden">
            <a href="/attractions/{{ $attractions->first()->slug }}">
                @if($attractions->first()->image)
                    <img src="{{ Storage::url($attractions->first()->image) }}" loading="lazy" class="w-full h-full object-cover min-h-[300px] md:min-h-full" alt="{{ $attractions->first()->name }}">
                @else
                    <div class="w-full h-full min-h-[300px] bg-[#d4e6cc] flex items-center justify-center text-6xl">🏞️</div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-4 left-4">
                    <h3 class="text-white text-lg font-medium">{{ $attractions->first()->name }}</h3>
                    @if($attractions->first()->village)
                        <p class="text-white/70 text-sm">{{ $attractions->first()->village->name }}</p>
                    @endif
                </div>
            </a>
        </div>

        {{-- Text กลาง --}}
        <div class="bg-white flex flex-col items-start justify-center px-8 py-10">
            <h2 class="text-[#2d5a27] text-2xl md:text-3xl font-semibold mb-3">
                {{ __('site.home.attractions_title_prefix') }}<span class="text-[#6db85c]">{{ __('site.home.attractions_title_highlight') }}</span>
            </h2>
            <p class="text-[#7a8c75] text-sm leading-relaxed mb-6">{{ __('site.home.attractions_description') }}</p>
            <a href="/attractions" class="inline-flex items-center gap-2 bg-[#2d5a27] text-white px-6 py-3 rounded-full text-sm font-medium hover:bg-[#3a6b33] transition-colors">
                {{ __('site.home.attractions_cta') }}
            </a>
        </div>

        {{-- Grid เล็กขวา --}}
        <div class="grid grid-rows-2">
            @foreach($attractions->skip(1)->take(2) as $attraction)
            <a href="/attractions/{{ $attraction->slug }}" class="relative overflow-hidden group">
                @if($attraction->image)
                    <img src="{{ Storage::url($attraction->image) }}" loading="lazy" class="w-full h-full object-cover min-h-[200px] group-hover:scale-105 transition-transform duration-300" alt="{{ $attraction->name }}">
                @else
                    <div class="w-full h-full min-h-[200px] bg-[#e8f5e3] flex items-center justify-center text-5xl">🏞️</div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-3 left-3">
                    <h3 class="text-white text-sm font-medium">{{ $attraction->name }}</h3>
                    @if($attraction->village)
                        <p class="text-white/70 text-xs">{{ $attraction->village->name }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>

    </div>
    @endif
</div>

<div class="h-px bg-[#d4e6cc] mx-4 md:mx-6"></div>

{{-- สินค้าชุมชน --}}
<div class="px-4 md:px-6 py-12">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-[#2d5a27] text-2xl md:text-3xl font-semibold mb-1">{{ __('site.home.products_title') }}</h2>
            <p class="text-[#7a8c75] text-sm">{{ __('site.home.products_subtitle') }}</p>
        </div>
        <a href="/products" class="inline-flex items-center gap-1 text-[#2d5a27] text-sm border border-[#2d5a27] px-4 py-2 rounded-full hover:bg-[#2d5a27] hover:text-white transition-colors">
            {{ __('site.home.products_view_all') }}
        </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($products as $product)
        <a href="/products/{{ $product->slug }}" class="bg-white border border-[#d4e6cc] rounded-2xl overflow-hidden block hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="h-52 bg-[#f0f8ee] flex items-center justify-center overflow-hidden">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" loading="lazy" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" alt="{{ $product->name }}">
                @else
                    <span class="text-6xl">🛍️</span>
                @endif
            </div>
            <div class="p-4">
                <h3 class="text-[#2d4a25] text-base font-medium mb-2">{{ $product->name }}</h3>
                <div class="flex items-center justify-between">
                    <div>
                        @if($product->price)
                            <p class="text-[#3a6b33] text-base font-semibold">฿{{ number_format($product->price, 0) }}
                                @if($product->unit)
                                    <span class="text-[#7a8c75] text-xs font-normal">/ {{ $product->unit }}</span>
                                @endif
                            </p>
                        @endif
                    </div>
                    @if($product->category)
                        <span class="inline-block bg-[#e8f5e3] text-[#3a6b33] text-xs px-3 py-1 rounded-full">
                            {{ match($product->category) {
                                'food', 'handicraft', 'health', 'other' => __('site.common.category.' . $product->category),
                                default => $product->category,
                            } }}
                        </span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>

<div class="h-px bg-[#d4e6cc] mx-4 md:mx-6"></div>

{{-- ข่าวสาร --}}
<div class="px-4 md:px-6 py-12">
    <div class="flex flex-col md:flex-row md:items-start gap-6 mb-10">
        <div class="flex-1">
            <h2 class="text-[#2d5a27] text-2xl md:text-3xl font-semibold mb-2">
                {{ __('site.home.posts_title_prefix') }}<span class="text-[#6db85c]">{{ __('site.home.posts_title_highlight') }}</span>
            </h2>
            <p class="text-[#7a8c75] text-sm leading-relaxed max-w-md">{{ __('site.home.posts_description') }}</p>
        </div>
        <a href="/posts" class="inline-flex items-center gap-2 bg-[#2d5a27] text-white px-6 py-3 rounded-full text-sm font-medium hover:bg-[#3a6b33] transition-colors self-start">
            {{ __('site.home.posts_cta') }}
        </a>
    </div>

    <div class="flex flex-col">
        @foreach($posts as $post)
        <a href="/posts/{{ $post->slug }}" class="flex items-start gap-6 md:gap-10 py-6 border-b border-[#e8f5e3] hover:bg-[#f7fdf5] transition-colors group px-2">
            {{-- วันที่ --}}
            <div class="flex-shrink-0 w-12">
                <div class="text-[#2d5a27] text-4xl font-semibold leading-none">{{ $post->published_at?->format('d') ?? $post->created_at->format('d') }}</div>
                <div class="text-[#6a9e62] text-base font-medium mt-1">{{ $post->published_at?->locale(app()->getLocale())->isoFormat('MMM') ?? $post->created_at->locale(app()->getLocale())->isoFormat('MMM') }}</div>
            </div>

            {{-- เนื้อหา --}}
            <div class="flex-1 min-w-0">
                <h3 class="text-[#2d4a25] text-base md:text-lg font-medium mb-2 group-hover:text-[#2d5a27] transition-colors leading-snug">{{ $post->title }}</h3>
                <div class="flex items-center gap-2">
                    <span class="text-[#2d5a27] group-hover:translate-x-1 transition-transform">→</span>
                    <p class="text-[#7a8c75] text-sm truncate">{{ Str::limit(strip_tags($post->content), 80) }}</p>
                </div>
            </div>

            {{-- รูป --}}
            @if($post->image)
            <div class="flex-shrink-0 w-32 md:w-48 h-24 md:h-32 rounded-xl overflow-hidden">
                <img src="{{ Storage::url($post->image) }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $post->title }}">
            </div>
            @endif
        </a>
        @endforeach
    </div>
</div>

{{-- 6 หมู่บ้าน --}}
<div class="px-4 md:px-6 py-10">
    <h2 class="text-[#2d5a27] text-lg font-medium mb-1">{{ __('site.home.villages_title') }}</h2>
    <p class="text-[#7a8c75] text-sm mb-5">{{ __('site.home.villages_subtitle') }}</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($villages as $village)
        <a href="/villages/{{ $village->slug }}" class="bg-white border border-[#d4e6cc] rounded-xl overflow-hidden hover:border-[#6db85c] hover:shadow-md transition-all group">
            <div class="h-40 bg-[#f0f8ee] flex items-center justify-center overflow-hidden">
                @if($village->image)
                    <img src="{{ Storage::url($village->image) }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $village->name }}">
                @else
                    <span class="text-5xl">🏘️</span>
                @endif
            </div>
            <div class="p-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-[#3a6b33] rounded-full flex items-center justify-center text-white text-sm font-medium flex-shrink-0">{{ $village->number }}</div>
                <div class="min-w-0">
                    <h3 class="text-[#2d4a25] text-sm font-medium">{{ $village->name }}</h3>
                    <p class="text-[#7a8c75] text-xs truncate">{{ $village->highlight }}</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>

{{-- แผนที่ชุมชน --}}
<div class="px-4 md:px-6 py-10 bg-[#f0f7ed]">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-[#2d5a27] text-lg font-medium mb-1">{{ __('site.home.map_title') }}</h2>
                <p class="text-[#7a8c75] text-sm">{{ __('site.home.map_subtitle') }}</p>
            </div>
            <a href="/map" class="hidden md:inline-flex items-center gap-1 text-[#2d5a27] text-sm border border-[#2d5a27] px-4 py-2 rounded-full hover:bg-[#2d5a27] hover:text-white transition-colors flex-shrink-0">
                {{ __('site.home.map_view_full') }}
            </a>
        </div>

        @include('partials.community-map', ['attractions' => $mapAttractions, 'villages' => $mapVillages, 'mapId' => 'home-community-map', 'mapHeight' => 'h-[400px]'])

        <a href="/map" class="md:hidden mt-4 inline-flex items-center gap-1 text-[#2d5a27] text-sm border border-[#2d5a27] px-4 py-2 rounded-full hover:bg-[#2d5a27] hover:text-white transition-colors">
            {{ __('site.home.map_view_full') }}
        </a>
    </div>
</div>

@endsection