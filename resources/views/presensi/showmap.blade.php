<!-- Map Container -->
<div id="map"></div>

<!-- Map Style -->
<style>
    #map {
        height: 250px;
        width: 100%;
        border-radius: 8px;
        margin-bottom: 20px;
    }
</style>

<!-- Leaflet JS Logic -->
<script>
    // Lokasi presensi dari database (format "lat,long")
var lokasiPresensi = "{{ $presensi->lokasi_in }}";

// Pisahkan menjadi latitude dan longitude
var presensiLatLong = lokasiPresensi.split(",");
var presensiLat = parseFloat(presensiLatLong[0]);
var presensiLong = parseFloat(presensiLatLong[1]);

// Lokasi kantor (supaya lingkaran bisa pas di kantor)
var lokasiKantor = "-8.132945,112.563983"; // bisa ambil dari DB juga
var kantorLatLong = lokasiKantor.split(",");
var kantorLat = parseFloat(kantorLatLong[0]);
var kantorLong = parseFloat(kantorLatLong[1]);
var radiusKantor = 30; // meter

// Inisialisasi peta
var mapPresensi = L.map('map').setView([presensiLat, presensiLong], 18);

// Tambahkan tile OpenStreetMap
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mapPresensi);

// Marker presensi karyawan
var markerPresensi = L.marker([presensiLat, presensiLong]).addTo(mapPresensi);

// Lingkaran area kantor
var circleKantor = L.circle([kantorLat, kantorLong], {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.5,
    radius: radiusKantor
}).addTo(mapPresensi);

// Popup nama karyawan
markerPresensi.bindPopup("{{ $presensi->nama_lengkap }}").openPopup();

</script>
