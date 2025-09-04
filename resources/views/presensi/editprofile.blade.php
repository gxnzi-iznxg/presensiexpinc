@extends('layouts.presensi')
@section('header')
    <!--- App Header --->
    <div class="appHeader bg-header text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Edit Profile</div>
        <div class="right"></div>
    </div>
        <!--- * App Header --->
@endsection

@section('content')
<div class="row" style="margin-top:4rem">
    <div class="col">
        @if (Session::has('success'))
    <div class="alert alert-success position-fixed top-0 mb-2" 
         role="alert" style="z-index:1050; max-width:300px;">
        {{ Session::get('success') }}
        
    </div>
@endif
@if (Session::has('error'))
        <div class="alert alert-danger position-fixed top-0 mb-2" 
         role="alert" style="z-index:1050; max-width:300px;">
        {{ Session::get('error') }}
        
    </div>
@endif

    </div>
</div>
@error('foto')
    <div class="alert alert-warning">
        <p>{{ $message }}</p>
    </div>
@enderror
<form action="/presensi/{{ $karyawan->nik }}/updateprofile" method="POST" enctype="multipart/form-data" style="margin-top:1rem">
    @csrf
    <div class="col">
        <div class="form-group boxed text-center">
            <div class="avatar">
                @php
                    $fotoPath = $karyawan->foto;
                    $defaultAvatar = asset('assets/img/sample/avatar/avatar1.jpg');
                    $fullFotoUrl = $fotoPath ? asset('storage/public/uploads/karyawan/' . $fotoPath) : $defaultAvatar;
                @endphp
                <img src="{{ $fullFotoUrl }}" 
                    alt="avatar" 
                    class="imaged w128 rounded mb-2" 
                    style="max-height:128px; max-width:128px; object-fit:cover;">
            </div>
            <small class="text-muted">Photo Profile</small>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="text" class="form-control" value="{{ $karyawan->nama_lengkap }}" name="nama_lengkap" placeholder="Nama Lengkap" autocomplete="off">
            </div>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="text" class="form-control" value="{{ $karyawan->no_hp }}" name="no_hp" placeholder="No. HP" autocomplete="off">
            </div>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off">
            </div>
        </div>
        
        <div class="custom-file-upload" id="fileUpload1">
            <input type="file" name="foto" id="fileuploadInput" accept=".png, .jpg, .jpeg">
            <label for="fileuploadInput">
                <span>
                    <strong>
                        <ion-icon name="cloud-upload-outline" role="img" class="md hydrated" aria-label="cloud upload outline"></ion-icon>
                        <i>Tap to Update Photo Profile</i>
                    </strong>
                </span>
            </label>
        </div>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <button type="submit" class="btn btn-header btn-block">
                    <ion-icon name="refresh-outline"></ion-icon>
                    Update
                </button>
            </div>
        </div>
    </div>
</form>
@endsection