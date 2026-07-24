@php
    $mapId = $mapId ?? 'community-map';
    $mapHeight = $mapHeight ?? 'h-[500px]';

    $mapAttractionMarkers = $attractions->map(fn ($attraction) => [
        'type' => 'attraction',
        'name' => $attraction->name,
        'lat' => $attraction->latitude,
        'lng' => $attraction->longitude,
        'url' => url('/attractions/' . $attraction->slug),
    ]);

    $mapVillageMarkers = $villages->map(fn ($village) => [
        'type' => 'village',
        'name' => $village->name,
        'lat' => $village->latitude,
        'lng' => $village->longitude,
        'url' => url('/villages/' . $village->slug),
        'products' => $village->products->map(fn ($product) => [
            'name' => $product->name,
            'url' => url('/products/' . $product->slug),
        ]),
    ]);
@endphp

<div class="bg-white border border-[#d4e6cc] rounded-xl overflow-hidden">
    <div id="{{ $mapId }}" class="w-full {{ $mapHeight }}"></div>
</div>

@if($attractions->isEmpty() && $villages->isEmpty())
    <p class="text-[#7a8c75] text-sm text-center mt-6">{{ __('site.map.empty_state') }}</p>
@endif

<div class="flex flex-wrap gap-4 mt-4 text-xs text-[#4a6a45]">
    <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-[#3a6b33] inline-block"></span> {{ __('site.map.legend_attraction') }}</span>
    <span class="flex items-center gap-2"><span class="w-4 h-4 rounded-full bg-[#d97706] inline-block"></span> {{ __('site.map.legend_village') }}</span>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
    .village-label-tooltip {
        background: #ffffff;
        border: 1px solid #d4e6cc;
        color: #2d4a25;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 9999px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .15);
        white-space: nowrap;
    }
    .village-label-tooltip::before {
        display: none;
    }
</style>
<script>
    (function () {
        const attractionMarkers = @json($mapAttractionMarkers);
        const villageMarkers = @json($mapVillageMarkers);
        const i18n = {
            attractionLabel: @json(__('site.map.legend_attraction')),
            productsInVillage: @json(__('site.map.products_in_village')),
            noProducts: @json(__('site.map.no_products')),
        };

        function createPinIcon(emoji, bgColor) {
            return L.divIcon({
                html: '<div style="background:' + bgColor + ';width:30px;height:30px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);display:flex;align-items:center;justify-content:center;box-shadow:0 1px 4px rgba(0,0,0,.4);"><span style="transform:rotate(45deg);font-size:14px;">' + emoji + '</span></div>',
                className: '',
                iconSize: [30, 30],
                iconAnchor: [15, 30],
                popupAnchor: [0, -28],
            });
        }

        const attractionIcon = createPinIcon('🏞️', '#3a6b33');
        const villageIcon = createPinIcon('🏘️', '#d97706');

        const defaultCenter = [6.5412, 101.2803];
        const firstPoint = attractionMarkers[0] || villageMarkers[0];
        const map = L.map('{{ $mapId }}').setView(
            firstPoint ? [firstPoint.lat, firstPoint.lng] : defaultCenter,
            13
        );

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        const allMarkers = [];

        attractionMarkers.forEach((point) => {
            const popup = '<a href="' + point.url + '"><strong>' + point.name + '</strong></a><br><span style="color:#7a8c75;">' + i18n.attractionLabel + '</span>';
            const marker = L.marker([point.lat, point.lng], { icon: attractionIcon }).addTo(map).bindPopup(popup);
            allMarkers.push(marker);
        });

        villageMarkers.forEach((point) => {
            let popup = '<a href="' + point.url + '"><strong>' + point.name + '</strong></a>';

            if (point.products.length) {
                popup += '<br><span style="color:#7a8c75;">' + i18n.productsInVillage + '</span><ul style="margin:4px 0 0;padding-left:16px;">';
                point.products.forEach((product) => {
                    popup += '<li><a href="' + product.url + '">' + product.name + '</a></li>';
                });
                popup += '</ul>';
            } else {
                popup += '<br><span style="color:#7a8c75;">' + i18n.noProducts + '</span>';
            }

            const marker = L.marker([point.lat, point.lng], { icon: villageIcon })
                .addTo(map)
                .bindPopup(popup)
                .bindTooltip(point.name, {
                    permanent: true,
                    direction: 'right',
                    offset: [8, -8],
                    className: 'village-label-tooltip',
                });
            allMarkers.push(marker);
        });

        if (allMarkers.length > 1) {
            const group = L.featureGroup(allMarkers);
            map.fitBounds(group.getBounds().pad(0.2));
        }
    })();
</script>
