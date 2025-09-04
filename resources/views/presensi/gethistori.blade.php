<style>
    .historicontent {
        display: flex;
        margin-top: 10px;
    }
    .datapresence {
        margin-left: 10px;
    }
</style>
@if ($histori->isEmpty())
    <div class="alert alert-warning text-center">Data tidak ditemukan untuk bulan dan tahun yang dipilih.</div>
@else
    <ul class="listview image-listview">
    </ul>

    @foreach($histori as $d)
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
@endif
