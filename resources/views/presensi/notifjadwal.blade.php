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
            <div class="alert alert-danger">
                <p>
                    Maaf, Anda Tidak Memiliki Jadwal Pada Hari Ini! <br>
                    Silahkan Hubungi HRD
                </p>
            </div>
        </div>
    </div>

@endsection