<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date("Y-m-d");
        $bulanini = date("m")*1;
        $tahunini = date("Y");
        $nik = Auth::guard('karyawan')->user()->nik;
        $presensihariini = DB::table('presensi')->where('nik', $nik)->where('tgl_presensi', $hariini)->first();
        $historibulanini = DB::table('presensi')->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            ->select('presensi.*', 'keterangan', 'jam_kerja.*', 'doc_sid', 'nama_cuti')
            ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->leftJoin('pengajuan_izin', 'presensi.kode_izin', '=', 'pengajuan_izin.kode_izin')
            ->leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
            ->where('presensi.nik',$nik)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
            ->orderBy('tgl_presensi', 'desc')
            ->get();

        $rekappresensi = DB::table('presensi')
        ->selectRaw('
        SUM(IF(status="H",1,0)) as jmlhadir,
        SUM(IF(status="I",1,0)) as jmlizin,
        SUM(IF(status="S",1,0)) as jmlsakit,
        SUM(IF(status="C",1,0)) as jmlcuti,
        SUM(IF(jam_in > jam_masuk ,1,0)) as jmltelat')
        ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
        ->where('nik',$nik)
        ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
        ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
        ->first();

        $leaderboard = DB::table('presensi')
        ->join('karyawan','presensi.nik','=','karyawan.nik')
        ->where('tgl_presensi', $hariini)
        ->orderBy('jam_in')
        ->get();
        
        $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

        

        return view('dashboard.dashboard', compact('presensihariini','historibulanini','namabulan','bulanini','tahunini','rekappresensi','leaderboard'));
    }


    public function dashboardadmin()
    {
        $bulanini = date("m");
        $tahunini = date("Y");
        $hariini = date("Y-m-d");

        $rekappresensi = DB::table('presensi')
        ->selectRaw('
        SUM(IF(status="H",1,0)) as jmlhadir,
        SUM(IF(status="I",1,0)) as jmlizin,
        SUM(IF(status="S",1,0)) as jmlsakit,
        SUM(IF(status="C",1,0)) as jmlcuti,
        SUM(IF(jam_in > jam_masuk ,1,0)) as jmltelat')
        ->leftJoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
        ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
        ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
        ->first();

        return view('dashboard.dashboardadmin', compact('rekappresensi'));
    }
}
