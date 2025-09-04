@extends('layouts.presensi')

@section('content')

<style>
    .logout {
        position: absolute;
        color: black;
        font-size: 30px;
        right: 8px;
    }

    .logout:hover {
        color: black;
    }

    .rekap {
        margin-bottom: -8px;
        margin-top: 10px;
        margin-left: 10px;
        text-align: center;
    }

    .presencedetail {
        display: flex;
        flex-direction: column; /* supaya otomatis ke bawah */
        gap: 6px; /* kasih jarak antar item */
    }

    .presencedetail span{
        display: block; /* biar rapi ke bawah */
        font-size: 12px;
    }
</style>

    <!-- App Capsule -->
        <div class="section" id="user-section">
            <a href="/proseslogout" class="logout">
                <ion-icon name="log-out-outline"></ion-icon>
            </a>
            <div id="user-detail">
                <div class="avatar">
                    @php
                        $fotoPath = Auth::guard('karyawan')->user()->foto;
                        $defaultAvatar = asset('assets/img/sample/avatar/avatar1.jpg'); // Path ke avatar default

                        // Jika menyimpan di storage/app/public/uploads/karyawan/
                        // maka aksesnya melalui public/public/storage/uploads/karyawan/
                        $fullFotoUrl = $fotoPath ? asset('storage/public/uploads/karyawan/' . $fotoPath) : $defaultAvatar;
                    @endphp
                    <img src="{{ $fullFotoUrl }}" alt="avatar" class="imaged w64 rounded">
                </div>
            <div id="user-info">
                <h3 id="user-name">{{ Auth::guard('karyawan')->user()->nama_lengkap }}</h3>
                <span id="user-role">{{ Auth::guard('karyawan')->user()->jabatan }}</span>
            </div>
        </div>
    </div>

        <div class="section" id="menu-section">
            <div class="card">
                <h3 class="rekap">Rekap Presensi Bulan {{ $namabulan[$bulanini] }} {{ $tahunini }}</h3>
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-3">
                            <div class="card">
                                <div class="card-body text-center" style="padding : 12px 12px !important; line-height:1rem">
                                    <span class="badge bg-danger" style="position: absolute; top:3px; right:5px; z-index:999">{{ $rekappresensi->jmlhadir }}</span>
                                    <ion-icon name="finger-print-outline" style="font-size: 1.6rem;" class="warning"></ion-icon>
                                    <br>
                                    <span style="font-size: 0.8rem; font-weight:500">Hadir</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card">
                                <div class="card-body text-center" style="padding : 12px 12px; line-height:1rem">
                                    <span class="badge bg-danger" style="position: absolute; top:3px; right:5px; z-index:999">{{ $rekappresensi->jmlizin }}</span>
                                    <ion-icon name="hand-left-outline" style="font-size: 1.6rem;" class="warning"></ion-icon>
                                    <br>
                                    <span style="font-size: 0.8rem; font-weight:500">Izin</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card">
                                <div class="card-body text-center" style="padding : 12px 12px; line-height:1rem">
                                    <span class="badge bg-danger" style="position: absolute; top:3px; right:5px; z-index:999">{{ $rekappresensi->jmlsakit }}</span>
                                    <ion-icon name="fitness-outline" style="font-size: 1.6rem;" class="warning"></ion-icon>
                                    <br>
                                    <span style="font-size: 0.8rem; font-weight:500">Sakit</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card">
                                <div class="card-body text-center" style="padding : 12px 12px; line-height:1rem">
                                    <span class="badge bg-danger" style="position: absolute; top:3px; right:5px; z-index:999">{{ $rekappresensi->jmlcuti }}</span>
                                    <ion-icon name="folder-outline" style="font-size: 1.6rem;" class="warning"></ion-icon>
                                    <br>
                                    <span style="font-size: 0.8rem; font-weight:500">Cuti</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section mt-2" id="presence-section">
            <div class="todaypresence">
                
                    <div class="col">
                        <div class="card gradasiyellow">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence">
                                        @if ($presensihariini != null)
                                        @php
                                            $path = Storage::url('/public/uploads/absensi/'.$presensihariini->foto_in);
                                        @endphp
                                        <img src="{{ url($path) }}" alt="" class="imaged w120">
                                        @else
                                             <ion-icon name="camera" style="font-size:150px"></ion-icon>
                                        @endif
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="presencetitlein">Masuk</h4>
                                        <span>{{ $presensihariini != null && $presensihariini->jam_in != null ? $presensihariini->jam_in : 'Belum Absen' }}</span>
                                        <br>
                                        <span>{{ $presensihariini != null && $presensihariini->tgl_presensi != null ? $presensihariini->tgl_presensi : '' }}</span>
                                        <br>
                                        <span>{{ $presensihariini != null && $presensihariini->lokasi_in != null ? 'Cepokomulyo, Kepanjen' : '' }}</span>
                                        <br>
                                        <span>{{ $presensihariini != null && $presensihariini->kode_jam_kerja != null ? $presensihariini->kode_jam_kerja : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col mt-2">
                        <div class="card gradasidark">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence">
                                        @if ($presensihariini != null && $presensihariini->jam_out != null)
                                        @php
                                            $path = Storage::url('/public/uploads/absensi/'.$presensihariini->foto_out);
                                        @endphp
                                        <img src="{{ url($path) }}" alt="" class="imaged w120">
                                        @else
                                             <ion-icon name="camera" style="font-size:150px"></ion-icon>
                                        @endif
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="presencetitleout">Pulang</h4>
                                        <span>{{ $presensihariini != null && $presensihariini->jam_out != null ? $presensihariini->jam_out : 'Belum Absen' }}</span>
                                        <br>
                                        <span>{{ $presensihariini != null && $presensihariini->tgl_presensi != null ? $presensihariini->tgl_presensi : '' }}</span>
                                        <br>
                                        <span>{{ $presensihariini != null && $presensihariini->lokasi_out != null ? 'Cepokomulyo, Kepanjen' : '' }}</span>
                                        <br>
                                        <span>{{ $presensihariini != null && $presensihariini->kode_jam_kerja != null ? $presensihariini->kode_jam_kerja : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
            </div>

            <div id="rekappresensi">
                
            <div class="row">
                
                
            </div>
            </div>            

            <div class="presencetab mt-2">
                <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                    <ul class="nav nav-tabs style1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                                Bulan Ini
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                                Leaderboard
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content mt-2" style="margin-bottom:100px;">
                    <div class="tab-pane fade show active" id="home" role="tabpanel">

                    <style>
                        .historicontent {
                            display: flex;
                            margin-top: 10px;
                        }
                        .datapresence {
                            margin-left: 10px;
                        }
                    </style>
                    @foreach ($historibulanini as $d)
                    @if ($d->status == "H")
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="historicontent">
                                    <div class="iconpresensi">
                                        <ion-icon name="qr-code-outline" style="font-size: 48px"></ion-icon>
                                    </div>
                                    <div class="datapresence">
                                        <h3 style="line-height: 2px">{{ $d->nama_jam_kerja }}</h3>
                                        <h4 style="margin: 0px !important">{{ DateToIndo2($d->tgl_presensi) }}</h4>
                                        <span>
                                            {!! $d->jam_in != null ? date("H:i",strtotime($d->jam_in)) : '<span class="danger">Belum Scan</span>' !!}
                                            {!! $d->jam_out != null ? "-" . date("H:i",strtotime($d->jam_out)) : '<span class="danger">- Belum Scan</span>' !!}
                                        </span>
                                        <div id="keterangan">
                                            @php
                                                //jam ketika karyawan absen
                                                $jam_in = date("H:i",strtotime($d->jam_in));
                                                //jam jadwal masuk
                                                $jam_masuk = date("H:i",strtotime($d->jam_masuk));

                                                $jadwal_jam_masuk = $d->tgl_presensi." ".$jam_masuk;
                                                $jam_presensi = $d->tgl_presensi." ".$jam_in;
                                            @endphp
                                            @if ($jam_in > $jam_masuk)
                                            @php
                                                $jmlterlambat = hitungjamterlambat($jadwal_jam_masuk, $jam_presensi);
                                                $jmlterlambatdesimal = hitungjamterlambatdesimal($jadwal_jam_masuk, $jam_presensi);
                                            @endphp
                                                <span class="danger">Terlambat {{ $jmlterlambat }} ({{ $jmlterlambatdesimal }} Jam)</span>
                                            @else
                                                <span style="color: yellow">Tepat Waktu</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @elseif($d->status == "I")
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="historicontent">
                                    <div class="iconpresensi">
                                        <ion-icon name="document-outline" style="font-size: 48px; color:rgb(255, 208, 0)"></ion-icon>
                                    </div>
                                    <div class="datapresence">
                                        <h3 style="line-height: 2px">IZIN - {{ $d->kode_izin }}</h3>
                                        <h4 style="margin: 0px !important">{{ DateToIndo2($d->tgl_presensi) }}</h4>
                                        <span>
                                            {{ $d->keterangan }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @elseif($d->status == "S")
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="historicontent">
                                    <div class="iconpresensi">
                                        <ion-icon name="medkit-outline" style="font-size: 48px; color:rgb(255, 208, 0)"></ion-icon>
                                    </div>
                                    <div class="datapresence">
                                        <h3 style="line-height: 2px">SAKIT - {{ $d->kode_izin }}</h3>
                                        <h4 style="margin: 0px !important">{{ DateToIndo2($d->tgl_presensi) }}</h4>
                                        <span>
                                            {{ $d->keterangan }}
                                        </span>
                                        <br>
                                        @if (!empty($d->doc_sid))
                                        <span style="color:rgb(0, 38, 163)">
                                            <ion-icon name="document-attach-outline"></ion-icon> Lihat Surat Dokter
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @elseif($d->status == "C")
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="historicontent">
                                    <div class="iconpresensi">
                                        <ion-icon name="calendar-outline" style="font-size: 48px; color:rgb(255, 208, 0)"></ion-icon>
                                    </div>
                                    <div class="datapresence">
                                        <h3 style="line-height: 2px">CUTI - {{ $d->kode_izin }}</h3>
                                        <h4 style="margin: 0px !important">{{ DateToIndo2($d->tgl_presensi) }}</h4>
                                        <span class="text-info">
                                            {{ $d->nama_cuti }}
                                        </span>
                                        <br>
                                        <span>
                                            {{ $d->keterangan }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endif
                        
                    @endforeach
                    </div>

                    <div class="tab-pane fade" id="profile" role="tabpanel">
                        <ul class="listview image-listview">
                            @foreach ($leaderboard as $d)
                            <li>
                                <div class="item">
                                    @php
                                        $fotoPath = Auth::guard('karyawan')->user()->foto;
                                        $defaultAvatar = asset('assets/img/sample/avatar/avatar1.jpg'); // Path ke avatar default

                                        // Jika menyimpan di storage/app/public/uploads/karyawan/
                                        // maka aksesnya melalui public/public/storage/uploads/karyawan/
                                        $fullFotoUrl = $fotoPath ? asset('storage/public/uploads/karyawan/' . $fotoPath) : $defaultAvatar;
                                    @endphp
                                        <img src="{{ $fullFotoUrl }}" alt="avatar" class="imaged w64 rounded">
                                    <div class="in">
                                        <div>
                                            <b>{{ $d->nama_lengkap }}</b><br>
                                            <small class="text-muted">{{ $d->jabatan }}</small>
                                        </div>
                                        <span class="badge {{ $d->jam_in < "07:00" ? "bg-header" : "bg-danger" }}">
                                            {{ $d->jam_in }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->

@endsection
