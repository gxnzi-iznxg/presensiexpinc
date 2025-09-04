@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-header text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Presensi EXP INC Software</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        .webcam-capture,
        .webcam-capture video{
            display:inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        #map {
            height: 180px;
            width: 100%;
            margin-top: 5px;
        }

        .jam-digital {
 
        background-color: #27272783;
        position: absolute;
        top: 65px;
        right: 13px;
        z-index: 9999;
        width: 150px;
        border-radius: 15px;
        padding: 10px;
    }
 
 
 
    .jam-digital p {
        color: #fff;
        font-size: 16px;
        text-align: left;
        margin-top: 0;
        margin-bottom: 0;
    }


    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@endsection

@section('content')
    <div class="row" style="margin-top: 60px">
        <div class="col">
            <input type="text" id="lokasi">
            <div class="webcam-capture"></div>
        </div>
    </div>
    <div class="jam-digital">
        <p>{{ date("d-m-Y") }}</p>
        <p id="jam"></p>
        <p>{{ $jamkerja->nama_jam_kerja }}</p>
        <p>Mulai : {{ date("H:i", strtotime($jamkerja->awal_jam_masuk)) }} </p>
        <p>Masuk : {{ date("H:i", strtotime($jamkerja->jam_masuk)) }}</p>
        <p>Berakhir : {{ date("H:i", strtotime($jamkerja->akhir_jam_masuk)) }}</p>
        <p>Pulang : {{ date("H:i", strtotime($jamkerja->jam_pulang)) }}</p>
    </div>
    <div class="row">
        <div class="col">
        @if ($cek > 0)
            <button id="takeabsen" class="btn btn-black btn-block">
            <ion-icon name="camera-outline"></ion-icon>
            Absen Pulang</button>
        @else
            <button id="takeabsen" class="btn btn-header btn-block">
            <ion-icon name="camera-outline"></ion-icon>
            Absen Masuk</button>
            @endif
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>

    <!-- Suara notifikasi -->
    <audio id="notifikasi_in">
        <source src="{{ asset('assets/sound/notifikasi_in.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_out">
        <source src="{{ asset('assets/sound/notifikasi_out.mp3') }}" type="audio/mpeg">
    </audio>

@endsection

@push('myscript')
    <script type="text/javascript">
        window.onload = function() {
            jam();
        }
    
        function jam() {
            var e = document.getElementById('jam')
                , d = new Date()
                , h, m, s;
            h = d.getHours();
            m = set(d.getMinutes());
            s = set(d.getSeconds());
    
            e.innerHTML = h + ':' + m + ':' + s;
    
            setTimeout('jam()', 1000);
        }
    
        function set(e) {
            e = e < 10 ? '0' + e : e;
            return e;
        }
    
    </script>
    <script>

        var notifikasi_in = document.getElementById('notifikasi_in');
        var notifikasi_out = document.getElementById('notifikasi_out');

        Webcam.set({
            height:480,
            width:640,
            image_format: 'jpeg',
            jpeg_quality: 80
        });

        Webcam.attach('.webcam-capture');

        var lokasi = document.getElementById('lokasi');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
        } else {
            alert('Geolocation tidak didukung oleh browser ini.');
        }

        function successCallback(position) {
            // Log detail lokasi untuk debugging
            console.log("Latitude:", position.coords.latitude);
            console.log("Longitude:", position.coords.longitude);
            console.log("Accuracy (in meters):", position.coords.accuracy);
            console.log("Timestamp:", new Date(position.timestamp));

            // Tampilkan lokasi di input hidden
            lokasi.value = position.coords.latitude + "," + position.coords.longitude;

            // Inisialisasi peta Leaflet
            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
            var lokasi_kantor = " {{ $lok_kantor->lokasi_kantor }}"
            var lok = lokasi_kantor.split(",");
            var lat_kantor = lok[0];
            var long_kantor = lok[1];
            var radius = "{{ $lok_kantor->radius }}";

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Marker posisi pengguna
            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);

            // Lingkaran akurasi (menggunakan nilai akurasi dari browser)
            var circle = L.circle([lat_kantor, long_kantor], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: radius // nilai akurasi langsung dari database
            }).addTo(map);

            // Atasi bug map tidak tampil penuh
            setTimeout(() => {
                map.invalidateSize();
            }, 500);
        }


        function errorCallback() {
            alert('Tidak dapat mengambil lokasi: ' + error.message);
            console.error('Geolocation error:', error);
        }

        $("#takeabsen").click(function(e) {
            e.preventDefault();
            
            Webcam.snap(function(uri) {
                image = uri;
                var lokasi = $("#lokasi").val();

                // Validasi sebelum kirim
                if (!lokasi) {
                    alert('Lokasi belum tersedia. Harap tunggu atau refresh halaman.');
                    button.prop('disabled', false).html(originalText);
                    return;
                }

                if (!image) {
                    alert('Gagal mengambil foto. Silakan coba lagi.');
                    button.prop('disabled', false).html(originalText);
                    return;
                }

            $.ajax({
                type:'POST',
                url:'/presensi/store',
                data:{
                    _token:"{{ csrf_token() }}",
                    image:image,
                    lokasi:lokasi
                },
                cache:false,
            success: function(respond) {
                if (respond.status === "success") {
                    // Putar suara
                    if (respond.tipe === "in") {
                        notifikasi_in.play().catch(err => console.warn("Gagal putar suara masuk:", err));
                    } else {
                        notifikasi_out.play().catch(err => console.warn("Gagal putar suara pulang:", err));
                    }

                    Swal.fire({
                    title: 'Berhasil!',
                    text: respond.message,
                    icon: 'success'
                });
        setTimeout(() => location.href = '/dashboard', 3000);
            } else {
                Swal.fire({
                title: 'Error!',
                text: respond.message,
                icon: 'error'
                    });
            }
            }
            });
        });
    });

    </script>
@endpush