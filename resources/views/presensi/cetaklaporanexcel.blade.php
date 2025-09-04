<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>A4 landscape</title>

  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
  <style>
  @page { size: A4 potret }
  #title {
        font-family : Arial, Helvetica, sans-serif;
        font-size: 18px;
        font-weight: bold;
    }
    .tabeldatakaryawan tr td {
        margin-top: 40px;
    }

    .tabeldatakaryawan tr td {
        padding: 5px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
    }
    .tabelpresensi {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }
    .tabelpresensi tr th{
        border: 1px solid rgb(10, 10, 10);
        padding: 8px;
        background-color: antiquewhite
    }
    .tabelpresensi tr td{
        border: 1px solid rgb(10, 10, 10);
        padding: 5px;
        font-size: 
    }
    .foto{
        width: 50px;
        height: 40px;
    }
  </style>
  
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4 potret">
    <?php
    function selisih($jam_masuk, $jam_keluar)
 {
    list($h, $m, $s) = explode(":", $jam_masuk);
    $dtAwal = mktime($h, $m, $s, "1", "1", "1");
    list($h, $m, $s) = explode(":", $jam_keluar);
    $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
    $dtSelisih = $dtAkhir - $dtAwal;
    $totalmenit = $dtSelisih / 60;
    $jam = explode(".", $totalmenit / 60);
    $sisamenit = ($totalmenit / 60) - $jam[0];
    $sisamenit2 = $sisamenit * 60;
    $jml_jam = $jam[0];
    return $jml_jam . ":" . round($sisamenit2);
}
    ?>

  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-10mm">

    <table style="width: 100%">
    <tr>
        <td style="width: 30px">
            <img src="{{ asset('assets/img/logo_presensi_perusahaan.png') }}" alt="">
        </td>
        <td id="title">
            <div>
                <span>
                    LAPORAN PRESENSI KARYAWAN <br>
                    PERIODE {{ strtoupper( $namabulan[$bulan])}} {{$tahun}}<br>
                    PT. EXP INC SOFTWARE
                </span>
            </div>
            <div>
                <span style="font-size: 14px; font-weight: normal; color: #000000; font-style: italic;">
                    We design learning interventions with games<br>
                    www.expinc.software
                </span>
            </div>
        </td>
    </tr>
</table>
<table class="tabeldatakaryawan">
    <tr>
        <td rowspan="6">
        </td>
    </tr>
    @if($karyawan)
        <tr>
            <td>NIK</td>
            <td>:</td>
            <td>{{ $karyawan->nik }}</td>
        </tr>
        <tr>
            <td>Nama Karyawan</td>
            <td>:</td>
            <td>{{ $karyawan->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>jabatan</td>
            <td>:</td>
            <td>{{ $karyawan->jabatan }}</td>
        </tr>
        <tr>
            <td style="font-style: italic">Departemen</td>
            <td>:</td>
            <td>{{ $karyawan->nama_dept }}</td>
        </tr>
        <tr>
            <td>No.Hp</td>
            <td>:</td>
            <td>{{ $karyawan->no_hp }}</td>
        </tr>
    @endif
</table>
<table class="tabelpresensi">
    <tr>
        <th>No.</th>
        <th>Tanggal</th>
        <th>Jam Masuk</th>
        <th>Jam Pulang</th>
        <th>Keterangan</th>
        <th>Jml Jam</th>
    </tr>
    @foreach ($presensi as $d)
    @php
        $jamterlambat = selisih($d->jam_masuk, $d->jam_in);
    @endphp
        <tr>
            <td style="text-align: center">{{ $loop->iteration }}</td>
            <td style="text-align: center">{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
            <td style="text-align: center">{{ $d->jam_in}}</td>
            <td style="text-align: center">{{$d->jam_out != null ? $d->jam_out : 'Belum Absen'}}</td>
            
            <td style="text-align: center">
                @if ($d->jam_in > $d->jam_masuk)
                Terlambat {{$jamterlambat}}
                @else
                Tepat Waktu
                @endif
            </td>
            <td style="text-align: center">
                @if ($d->jam_out != null)
                @php
                    $jmljamkerja = selisih($d->jam_in, $d->jam_out);
                @endphp
                @else
                @php
                $jmljamkerja =0;
                @endphp  
                @endif
                {{ $jmljamkerja }}
            </td>
        </tr>
    @endforeach
</table>
<table width="100%" style="margin-top: 100px">
    <tr>
        <td colspan="2" style="text-align: right">Malang, {{ date('d-m-Y') }}</td>
    </tr>
    <tr>
        <td style="text-align: center; vertical-align:bottom" height="100px">
            <u>Bagus</u><br>
            <i><b>HRD Manager</b></i>
        </td>
        <td style="text-align: center; vertical-align:bottom">
            <u>Setya</u><br>
            <i><b>Direktur</b></i>
        </td>
    </tr>
</table>
  </section>

</body>

</html>