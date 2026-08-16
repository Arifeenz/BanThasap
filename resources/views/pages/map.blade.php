@extends('layouts.public')

@section('title', 'แผนที่ชุมชน - ตำบลท่าสาป')
@section('description', __('site.map.header_subtitle'))

@section('content')

<div class="px-6 py-10 max-w-5xl mx-auto pt-20">
    <a href="/attractions" class="text-[#3a6b33] text-sm mb-6 inline-block">{{ __('site.common.back') }}</a>

    <h1 class="text-[#2d4a25] text-xl font-medium mb-1">{{ __('site.map.header_title') }}</h1>
    <p class="text-[#7a8c75] text-sm mb-6">{{ __('site.map.header_subtitle') }}</p>

    @include('partials.community-map')
</div>

@endsection
