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
  @page { size: A4 landscape }
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
        background-color: antiquewhite;
        font-size: 12px;
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
<body class="A4 landscape">
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
            <span>
                REKAP PRESENSI KARYAWAN <br>
                PERIODE {{ strtoupper( $namabulan[$bulan])}} {{$tahun}}<br>
                EXP INC Software
            </span>
            <span>Jl. Kodim no.75, kec. kusosno. kab.malang</span>
        </td>
    </tr>
</table>
<table class="tabelpresensi">
    <thead>
        <tr>
            <th rowspan="2">NIK</th>
            <th rowspan="2">Nama Karyawan</th>
            <th colspan="31">Tanggal</th>
            <th rowspan="2">TH</th>
            <th rowspan="2">TT</th>
             <!-- Ini header gabungan -->
        </tr>
        <tr>
            @for ($i = 1; $i <= 31; $i++)
                <th>{{ $i }}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach ($rekap as $d)
    <tr>
        <td>{{ $d->nik }}</td>
        <td>{{ $d->nama_lengkap }}</td>

        @php
            $totalhadir = 0;
        @endphp
        @php
            $totalterlambat = 0;
        @endphp
        @for ($i = 1; $i <= 31; $i++)
            @php
                $tgl = 'tgl_' . $i;
                if (empty($d->$tgl)) {
                    $hadir = ['', ''];
                } else {
                    $hadir = explode("-", $d->$tgl);
                    $totalhadir += 1;
                    if ($hadir[0] > "08:00:00") {
                        $totalterlambat +=1;
                    }
                }
            @endphp
            <td>
                <span style="color: {{ $hadir[0] > '08:00:00' ? 'red' : 'black' }}">{{ $hadir[0] }}</span><br>
                <span style="color: {{ $hadir[1] < '16:00:00' ? 'red' : 'black' }}">{{ $hadir[1] }}</span>
            </td>
        @endfor

        <td>{{ $totalhadir }}</td>
        <td>{{ $totalterlambat}}</td>
    </tr>
@endforeach


    </tbody>
</table>

<table width="100%" style="margin-top: 100px">
    <tr>
        <td></td>
        <td style="text-align: center">Malang, {{ date('d-m-Y') }}</td>
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