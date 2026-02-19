@props([
    'latitude' => get_setting('map_latitude', '52.3676'),
    'longitude' => get_setting('map_longitude', '4.9041'),
    'zoom' => get_setting('map_zoom', '15'),
    'interactive' => false,
    'id' => 'map-' . uniqid('', true),
])

<div id="{{ $id }}" {{ $attributes->merge(['class' => 'w-full h-96 rounded-lg z-10']) }}></div>

@pushOnce('styles')
<link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.min.css') }}">
@endPushOnce

@pushOnce('scripts')
<script src="{{ asset('assets/leaflet/leaflet.min.js') }}"></script>
@endPushOnce

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lat = {{ $latitude }};
    const lng = {{ $longitude }};
    const zoom = {{ $zoom }};
    const mapId = '{{ $id }}';
    const isInteractive = {{ $interactive ? 'true' : 'false' }};

    if (document.getElementById(mapId)) {
        const map = L.map(mapId, {
            scrollWheelZoom: isInteractive,
            dragging: isInteractive,
            touchZoom: isInteractive,
            doubleClickZoom: isInteractive,
            boxZoom: isInteractive,
            keyboard: isInteractive,
        }).setView([lat, lng], zoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const marker = L.marker([lat, lng], {
            draggable: isInteractive,
        }).addTo(map);

        if (isInteractive) {
            // Logic for admin map interaction
            const latInput = document.getElementById('map_latitude');
            const lonInput = document.getElementById('map_longitude');

            const updateInputs = (latlng) => {
                if (latInput && lonInput) {
                    latInput.value = latlng.lat.toFixed(6);
                    lonInput.value = latlng.lng.toFixed(6);
                }
            };

            marker.on('dragend', function (e) {
                updateInputs(e.target.getLatLng());
            });

            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng);
            });
        }
    }
});
</script>
@endpush
