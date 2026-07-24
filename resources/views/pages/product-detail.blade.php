@extends('layouts.public')

@section('title', $product->name . ' - ตำบลท่าสาป')

@section('content')

<div class="px-6 py-10 max-w-3xl mx-auto pt-20">
    <a href="/products" class="text-[#3a6b33] text-sm mb-6 inline-block">{{ __('site.common.back') }}</a>

    <div class="bg-white border border-[#d4e6cc] rounded-xl overflow-hidden">
        @if($product->images->isNotEmpty())
            @php
                $coverImage = $product->images->firstWhere('is_cover', true) ?? $product->images->first();
            @endphp
            <img id="product-main-image" src="{{ Storage::url($coverImage->image) }}" class="w-full object-contain max-h-[600px]" alt="{{ $product->name }}">

            @if($product->images->count() > 1)
                <div class="flex gap-2 p-3 overflow-x-auto bg-white border-b border-[#d4e6cc]">
                    @foreach($product->images as $image)
                        <button
                            type="button"
                            onclick="document.getElementById('product-main-image').src = '{{ Storage::url($image->image) }}'"
                            class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 {{ $image->is($coverImage) ? 'border-[#3a6b33]' : 'border-transparent' }} hover:border-[#3a6b33] transition-colors"
                        >
                            <img src="{{ Storage::url($image->image) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                        </button>
                    @endforeach
                </div>
            @endif
        @endif
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-[#2d4a25] text-xl font-medium mb-1">{{ $product->name }}</h1>
                    @if($product->category)
                        <span class="inline-block bg-[#e8f5e3] text-[#3a6b33] text-xs px-2 py-0.5 rounded-full mt-1">
                            {{ match($product->category) {
                                'food', 'handicraft', 'health', 'other' => __('site.common.category.' . $product->category),
                                default => $product->category,
                            } }}
                        </span>
                    @endif
                </div>
                @if($product->price)
                    <p class="text-[#3a6b33] text-xl font-medium">฿{{ number_format($product->price, 0) }}
                        @if($product->unit)
                            <span class="text-[#7a8c75] text-sm font-normal">/ {{ $product->unit }}</span>
                        @endif
                    </p>
                @endif
            </div>

            @if($product->description)
                <div class="prose text-sm text-[#4a6a45] mb-6">{!! $product->description !!}</div>
            @endif

            @if($product->story)
                <div class="bg-[#f0f8ee] rounded-xl p-4 mb-6">
                    <h2 class="text-[#2d5a27] text-sm font-medium mb-2">{{ __('site.products.story_title') }}</h2>
                    <div class="prose text-sm text-[#4a6a45]">{!! $product->story !!}</div>
                </div>
            @endif

            @if($product->contact)
                <div class="border border-[#d4e6cc] rounded-xl p-4">
                    <h2 class="text-[#2d5a27] text-sm font-medium mb-2">{{ __('site.products.contact_order_title') }}</h2>
                    <p class="text-[#4a6a45] text-sm">{{ $product->contact }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection