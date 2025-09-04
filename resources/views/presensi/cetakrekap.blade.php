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
        table-layout: fixed;
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
        font-size: 10px;
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
<table class="tabelpresensi">
        <tr>
            <th style="width: 25px;" rowspan="2">NIK</th>
            <th style="width: 100px;" rowspan="2">Nama Karyawan</th>
            <th colspan="{{ $jmlhari }}">Bulan {{ $namabulan[$bulan] }} {{ $tahun }}</th>
            <th style="width: 15px;" rowspan="2">H</th>
            <th style="width: 15px;" rowspan="2">I</th>
            <th style="width: 15px;" rowspan="2">S</th>
            <th style="width: 15px;" rowspan="2">C</th>
            <th style="width: 15px;" rowspan="2">A</th>
        </tr>
        <tr>
            @foreach ($rangetanggal as $d)
            @if ($d != NULL)
                <th>{{ date("d", strtotime($d)) }}</th>
            @endif
            @endforeach
        </tr>
        @foreach ($rekap as $r)
            <tr>
                <td style="text-align: center">{{ $r->nik }}</td>
                <td>{{ $r->nama_lengkap }}</td>
                    <?php
                        $jml_hadir = 0;
                        $jml_izin = 0;
                        $jml_sakit = 0;
                        $jml_cuti = 0;
                        $jml_alpa = 0;
                        $color = "";

                        for($i=1; $i<=$jmlhari; $i++) {
                            $tgl = "tgl_".$i;
                            $datapresensi = explode("|", $r->$tgl);
                            if($r->$tgl != NULL) {
                            $status = $datapresensi[2];
                            } else {
                                $status = "";
                            }

                            if($status == "H") {
                                $jml_hadir += 1;
                                $color = "white";
                            }

                            if($status == "S") {
                                $jml_izin += 1;
                                $color = "yellow";
                            }

                            if($status == "I") {
                                $jml_sakit += 1;
                                $color = "purple";
                            }

                            if($status == "C") {
                                $jml_cuti += 1;
                                $color = "cyan";
                            }

                            if(empty($status)) {
                                $jml_alpa += 1;
                                $color = "red";
                            }
                    ?>
                    <td style="text-align: center; background-color: {{ $color }}">
                        {{ $status }}
                    </td>
                    <?php
                        }
                    ?>
                    <td style="text-align: center">{{ !empty($jml_hadir) ? $jml_hadir : "" }}</td>
                    <td style="text-align: center">{{ !empty($jml_izin) ? $jml_izin : "" }}</td>
                    <td style="text-align: center">{{ !empty($jml_sakit) ? $jml_sakit : "" }}</td>
                    <td style="text-align: center">{{ !empty($jml_cuti) ? $jml_cuti : "" }}</td>
                    <td style="text-align: center">{{ !empty($jml_alpa) ? $jml_alpa : "" }}</td>
            </tr>
        @endforeach

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