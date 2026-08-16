<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($staticUrls as $item)
    <url>
        <loc>{{ $item['url'] }}</loc>
        <priority>{{ $item['priority'] }}</priority>
    </url>
    @endforeach
    @foreach($products as $product)
    <url>
        <loc>{{ url('/products/'.$product->slug) }}</loc>
        <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
        <priority>0.6</priority>
    </url>
    @endforeach
    @foreach($attractions as $attraction)
    <url>
        <loc>{{ url('/attractions/'.$attraction->slug) }}</loc>
        <lastmod>{{ $attraction->updated_at->toAtomString() }}</lastmod>
        <priority>0.6</priority>
    </url>
    @endforeach
    @foreach($posts as $post)
    <url>
        <loc>{{ url('/posts/'.$post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
        <priority>0.5</priority>
    </url>
    @endforeach
    @foreach($villages as $village)
    <url>
        <loc>{{ url('/villages/'.$village->slug) }}</loc>
        <lastmod>{{ $village->updated_at->toAtomString() }}</lastmod>
        <priority>0.6</priority>
    </url>
    @endforeach
</urlset>
