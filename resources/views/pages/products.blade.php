@extends('layouts.public')

@section('title', 'สินค้าชุมชน - ตำบลท่าสาป')

@section('content')

<div class="bg-[#3a6b33] px-6 py-10 text-center pt-28">
    <h1 class="text-[#e8f5e3] text-2xl font-medium mb-2">{{ __('site.products.header_title') }}</h1>
    <p class="text-[#b8d8b0] text-sm">{{ __('site.products.header_subtitle') }}</p>
</div>

{{-- Filter Bar --}}
<div class="px-6 py-4 bg-white border-b border-[#d4e6cc] sticky z-40" style="top: var(--navbar-offset, 6.5rem); transition: top .3s ease;">
    <div class="flex flex-wrap gap-2">
        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}"
           class="px-4 py-1.5 rounded-full text-sm transition-colors {{ !request('category') ? 'bg-[#2d5a27] text-white' : 'bg-[#f0f7ed] text-[#3a6b33] hover:bg-[#d4e6cc]' }}">
            {{ __('site.common.category.all') }}
        </a>
        <a href="{{ request()->fullUrlWithQuery(['category' => 'food']) }}"
           class="px-4 py-1.5 rounded-full text-sm transition-colors {{ request('category') === 'food' ? 'bg-[#2d5a27] text-white' : 'bg-[#f0f7ed] text-[#3a6b33] hover:bg-[#d4e6cc]' }}">
            🍱 {{ __('site.common.category.food') }}
        </a>
        <a href="{{ request()->fullUrlWithQuery(['category' => 'handicraft']) }}"
           class="px-4 py-1.5 rounded-full text-sm transition-colors {{ request('category') === 'handicraft' ? 'bg-[#2d5a27] text-white' : 'bg-[#f0f7ed] text-[#3a6b33] hover:bg-[#d4e6cc]' }}">
            🧺 {{ __('site.common.category.handicraft') }}
        </a>
        <a href="{{ request()->fullUrlWithQuery(['category' => 'health']) }}"
           class="px-4 py-1.5 rounded-full text-sm transition-colors {{ request('category') === 'health' ? 'bg-[#2d5a27] text-white' : 'bg-[#f0f7ed] text-[#3a6b33] hover:bg-[#d4e6cc]' }}">
            🌿 {{ __('site.common.category.health') }}
        </a>
        <a href="{{ request()->fullUrlWithQuery(['category' => 'other']) }}"
           class="px-4 py-1.5 rounded-full text-sm transition-colors {{ request('category') === 'other' ? 'bg-[#2d5a27] text-white' : 'bg-[#f0f7ed] text-[#3a6b33] hover:bg-[#d4e6cc]' }}">
            {{ __('site.common.category.other') }}
        </a>
    </div>
</div>

<div class="px-6 py-10">
    @if($products->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($products as $product)
        <a href="/products/{{ $product->slug }}" class="bg-white border border-[#d4e6cc] rounded-2xl overflow-hidden block hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="h-48 bg-[#f0f8ee] flex items-center justify-center overflow-hidden">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" loading="lazy" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" alt="{{ $product->name }}">
                @else
                    <span class="text-5xl">🛍️</span>
                @endif
            </div>
            <div class="p-4">
                <h3 class="text-[#2d4a25] text-sm font-medium mb-2">{{ $product->name }}</h3>
                <div class="flex items-center justify-between">
                    @if($product->price)
                        <p class="text-[#3a6b33] text-sm font-semibold">฿{{ number_format($product->price, 0) }}
                            @if($product->unit)
                                <span class="text-[#7a8c75] text-xs font-normal">/ {{ $product->unit }}</span>
                            @endif
                        </p>
                    @endif
                    @if($product->category)
                        <span class="inline-block bg-[#e8f5e3] text-[#3a6b33] text-xs px-2 py-0.5 rounded-full">
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
    <div class="mt-8">{{ $products->links() }}</div>
    @else
    <div class="text-center py-20">
        <div class="text-5xl mb-4">🛍️</div>
        <p class="text-[#7a8c75]">{{ __('site.products.empty_state') }}</p>
    </div>
    @endif
</div>

@endsection