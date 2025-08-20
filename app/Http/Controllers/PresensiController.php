<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Presensi;
use App\Models\Pengajuanizin;


class PresensiController extends Controller
{
    public function create()
    {
        $hariini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        $lok_kantor = DB::table("konfigurasi_lokasi")->where("id", 1)->first();
        # dd($lokasi_kantor);
        return view('presensi.create', compact('cek', 'lok_kantor'));
    }

    
    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lok_kantor = DB::table("konfigurasi_lokasi")->where("id", 1)->first();
        $lok = explode(",", $lok_kantor->lokasi_kantor);
        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];

        $cek = DB::table('presensi')
            ->where('tgl_presensi', $tgl_presensi)
            ->where('nik', $nik)
            ->count();

        if ($cek > 0) {
            // Absen Pulang
            $ket = "out";
            $formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
            $fileName = $formatName . ".jpeg";
        } else {
            // Absen Masuk
            $ket = "in";
            $formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
            $fileName = $formatName . ".jpeg";
        }

        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();
        if ($radius > $lok_kantor->radius) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf Anda Berada Diluar Radius! Jarak Anda ' . $radius . ' meter dari Kantor',
                'type' => 'out'
            ]);

            
        }
            $cekPresensi = DB::table('presensi')
                ->where('tgl_presensi', $tgl_presensi)
                ->where('nik', $nik)
                ->first();

        if ($cekPresensi && is_null($cekPresensi->jam_out)) {
            // ====== Absen Pulang ======
            $jamSekarang = date("H:i:s");
            $batasPulang = "16:00:00";

            if ($jamSekarang < $batasPulang) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Absen pulang hanya bisa dilakukan setelah jam 16:00',
                    'type' => 'out'
                ]);
            }

            $data_pulang = [
                    'jam_out' => $jam,
                    'foto_out' => $fileName,
                    'lokasi_out' => $lokasi
            ];

            $update = DB::table('presensi')
                ->where('tgl_presensi', $tgl_presensi)
                ->where('nik', $nik)
                ->update($data_pulang);
            
            if($update){
                Storage::put($file, $image_base64);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Terima Kasih, Hati-Hati Di Jalan',
                    'type' => 'out'
                ]);

            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Maaf Gagal Absen, Hubungi Tim IT',
                    'type' => 'out'
                ]);
            }

        } else {
            // ====== Absen Masuk ======
            $jamSekarang = date("H:i:s");
            $batasAwal = "04:50:00";
            $batasAkhir = "08:00:00";

            if ($jamSekarang < $batasAwal) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Absen masuk baru bisa mulai jam 04:50',
                    'type' => 'in'
                ]);
            }

            $keterangan = ($jamSekarang > $batasAkhir) ? "Terlambat" : "Tepat Waktu";

            $data = [
                'nik' => $nik,
                'tgl_presensi' => $tgl_presensi,
                'jam_in' => $jamSekarang,
                'foto_in' => $fileName,
                'lokasi_in' => $lokasi,
                'status' => 'h'
            ];
                
            $simpan = DB::table('presensi')->insert($data);
        
            if ($simpan) {
                Storage::put($file, $image_base64);
                return response()->json([
                    'status' => 'success',
                    'message' => "Terima Kasih, Selamat Bekerja ($keterangan)",
                    'type' => 'in'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Maaf Gagal Absen, Hubungi Tim IT',
                    'type' => 'in'
                ]);
            }
        }
    }

    //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }


    public function editprofile()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('presensi.editprofile', compact('karyawan'));
    }


    public function updateprofile(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        // Data yang akan diupdate ke database
        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'no_hp' => $request->no_hp,
        ];

        // 1. Handle Password
        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        // 2. Handle Foto
        // Inisialisasi dengan foto lama, jika tidak ada upload baru, ini yang akan disimpan
        $data['foto'] = $karyawan->foto;

        if ($request->hasFile('foto')) {
            $foto_file = $request->file('foto');
            $extension = $foto_file->getClientOriginalExtension();
            $file_name = $nik . "." . $extension; // Nama file yang benar: NIK.ekstensi
            $folderPath = "public/uploads/karyawan/";

            try {
                // Hapus foto lama jika ada dan bukan foto default
                // Penting untuk membersihkan file lama agar tidak menumpuk
                if ($karyawan->foto && Storage::exists($folderPath . $karyawan->foto) && $karyawan->foto !== 'default_avatar.jpg' /* atau nama file default Anda */) {
                    Storage::delete($folderPath . $karyawan->foto);
                }

                // Pindahkan file dari temp PHP ke lokasi permanen storage/app/public/uploads/karyawan/
                $foto_file->storeAs($folderPath, $file_name);
                $data['foto'] = $file_name; // BARIS INI PENTING! Update 'foto' di $data dengan nama file yang benar
            } catch (\Exception $e) {
                \Log::error("File upload error for NIK: $nik - " . $e->getMessage());
                return redirect()->back()->with('error', 'Profil berhasil diperbarui, tetapi **foto gagal diunggah**. Pastikan file valid dan ukuran tidak melebihi batas. Error: ' . $e->getMessage());
            }
        }

        // 3. Update Database
        $update = DB::table('karyawan')->where('nik', $nik)->update($data);

        if ($update) {
            return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
        } else {
            return redirect()->back()->with('error', 'Profil gagal diperbarui atau tidak ada perubahan data.');
        }
    }


    public function histori()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.histori', compact('namabulan'));
    }


    public function gethistori(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        $histori = DB::table('presensi')
            ->whereRaw('MONTH(tgl_presensi) = ' . $bulan)
            ->whereRaw('YEAR(tgl_presensi) = ' . $tahun)
            ->where('nik', $nik)
            ->orderBy('tgl_presensi')
            ->get();

        return view('presensi.gethistori', compact('histori'));
    }


    public function izin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        if(!empty($request->bulan) && !empty($request->tahun)){
            $dataizin = DB::table('pengajuan_izin')
            ->leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
            ->orderBy('tgl_izin_dari', 'desc')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_izin_dari)="'.$request->bulan.'"')
            ->whereRaw('YEAR(tgl_izin_dari)="'.$request->tahun.'"')
            ->get();
        } else {

            $dataizin = DB::table('pengajuan_izin')
            ->leftJoin('master_cuti', 'pengajuan_izin.kode_cuti', '=', 'master_cuti.kode_cuti')
            ->orderBy('tgl_izin_dari', 'desc')
            ->where('nik', $nik)->limit(5)
            ->orderBy('tgl_izin_dari','desc')
            ->get();
        }
            $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
            return view('presensi.izin', compact('dataizin', 'namabulan'));
    }


    public function buatizin()
    {
        return view('presensi.buatizin');
    }


    public function storeizin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'nik' => $nik,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan
        ];

        $simpan = DB::table('pengajuan_izin')->insert($data);

        if ($simpan) {
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
        }
    }


    public function monitoring()
    {
        return view('presensi.monitoring');
    }


    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')
            ->select('presensi.*', 'nama_lengkap', 'nama_dept')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->where('tgl_presensi', $tanggal)
            ->get();

        return view('presensi.getpresensi', compact('presensi'));
    }


    public function tampilkanpeta(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensi')->where('id', $id)
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->first();
        return view('presensi.showmap', compact('presensi'));
    }


    public function laporan()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('namabulan', 'karyawan'));
    }

    public function cetaklaporan(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan')->where('nik', $nik)
            ->join ('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->first();
        $presensi = DB::table('presensi')
            ->where('nik', $nik)
            ->whereRaw('MONTH (tgl_presensi) = "' . $bulan . '"')
            ->whereRaw('YEAR (tgl_presensi) = "' . $tahun . '"')
            ->orderBy('tgl_presensi')
            ->get();
       

        // if ($nik == "all") {
        //     $laporan = DB::table('presensi')
        //         ->whereRaw('MONTH(tgl_presensi) = ' . $bulan)
        //         ->whereRaw('YEAR(tgl_presensi) = ' . $tahun)
        //         ->orderBy('tgl_presensi')
        //         ->get();
        // } else {
        //     $laporan = DB::table('presensi')
        //         ->whereRaw('MONTH(tgl_presensi) = ' . $bulan)
        //         ->whereRaw('YEAR(tgl_presensi) = ' . $tahun)
        //         ->where('nik', $nik)
        //         ->orderBy('tgl_presensi')
        //         ->get();
        // }

        if(isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            //fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            //mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Presensi Karyawan $time.xls");
            return view('presensi.cetaklaporanexcel', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi'));
        }

        return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'karyawan', 'presensi'));
    }


    public function rekap()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        
        return view('presensi.rekap', compact('namabulan'));
    }


    public function cetakrekap(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $rekap = DB::table('presensi')
        ->selectRaw('presensi.nik,nama_lengkap,
            MAX(IF(DAY(tgl_presensi) = 1,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_1,
            MAX(IF(DAY(tgl_presensi) = 2,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_2,
            MAX(IF(DAY(tgl_presensi) = 3,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_3,
            MAX(IF(DAY(tgl_presensi) = 4,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_4,
            MAX(IF(DAY(tgl_presensi) = 5,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_5,
            MAX(IF(DAY(tgl_presensi) = 6,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_6,
            MAX(IF(DAY(tgl_presensi) = 7,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_7,
            MAX(IF(DAY(tgl_presensi) = 8,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_8,
            MAX(IF(DAY(tgl_presensi) = 9,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_9,
            MAX(IF(DAY(tgl_presensi) = 10,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_10,
            MAX(IF(DAY(tgl_presensi) = 11,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_11,
            MAX(IF(DAY(tgl_presensi) = 12,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_12,
            MAX(IF(DAY(tgl_presensi) = 13,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_13,
            MAX(IF(DAY(tgl_presensi) = 14,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_14,
            MAX(IF(DAY(tgl_presensi) = 15,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_15,
            MAX(IF(DAY(tgl_presensi) = 16,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_16,
            MAX(IF(DAY(tgl_presensi) = 17,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_17,
            MAX(IF(DAY(tgl_presensi) = 18,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_18,
            MAX(IF(DAY(tgl_presensi) = 19,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_19,
            MAX(IF(DAY(tgl_presensi) = 20,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_20,
            MAX(IF(DAY(tgl_presensi) = 21,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_21,
            MAX(IF(DAY(tgl_presensi) = 22,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_22,
            MAX(IF(DAY(tgl_presensi) = 23,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_23,
            MAX(IF(DAY(tgl_presensi) = 24,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_24,
            MAX(IF(DAY(tgl_presensi) = 25,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_25,
            MAX(IF(DAY(tgl_presensi) = 26,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_26,
            MAX(IF(DAY(tgl_presensi) = 27,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_27,
            MAX(IF(DAY(tgl_presensi) = 28,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_28,
            MAX(IF(DAY(tgl_presensi) = 29,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_29,
            MAX(IF(DAY(tgl_presensi) = 30,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_30,
            MAX(IF(DAY(tgl_presensi) = 31,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_31')
        ->join('karyawan','presensi.nik', '=', 'karyawan.nik')
        ->whereRaw('MONTH(tgl_presensi)="' .$bulan . '"')
        ->whereRaw('YEAR(tgl_presensi)="' .$tahun . '"')
        ->groupByRaw('presensi.nik,nama_lengkap')
        ->get();

        if(isset($_POST['exportexcel'])) {
            $time = date("d-M-Y H:i:s");
            //fungsi header dengan mengirimkan raw data excel
            header("Content-type: application/vnd-ms-excel");
            //mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Presensi Karyawan $time.xls");
        }

        return view('presensi.cetakrekap', compact('bulan','tahun','namabulan','rekap'));
    }


    public function izinsakit(Request $request)
    {
        $query = Pengajuanizin::query();
        $query->select('kode_izin', 'tgl_izin_dari', 'tgl_izin_sampai', 'pengajuan_izin.nik', 'nama_lengkap', 'jabatan', 'status', 'status_approved', 'keterangan');
        $query->join('karyawan', 'pengajuan_izin.nik', '=', 'karyawan.nik');

        if(!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_izin_dari', [$request->dari, $request->sampai]);
        }

        if(!empty($request->nik)) {
            $query->where('pengajuan_izin.nik', $request->nik);
        }

        if(!empty($request->nama_lengkap)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }
        
        if($request->status_approved == '0' || $request->status_approved == '1' || $request->status_approved == '2') {
            $query->where('status_approved', $request->status_approved);
        }

        $query->orderBy('tgl_izin_dari', 'desc');
        $izinsakit = $query->paginate(5);
        $izinsakit->appends($request->all());

        return view('presensi.izinsakit', compact('izinsakit'));
    }

    
    public function approveizinsakit(Request $request)
    {
        $status_approved = $request->status_approved;
        $kode_izin = $request->kode_izin_form;
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $nik = $dataizin->nik;
        $tgl_dari = $dataizin->tgl_izin_dari;
        $tgl_sampai = $dataizin->tgl_izin_sampai;
        $status = $dataizin->status;
        DB::beginTransaction();

        try {
            if($status_approved == 1) {
                while (strtotime($tgl_dari) <= strtotime($tgl_sampai)) {
                    DB::table('presensi')->insert([
                        'nik'           => $nik,
                        'tgl_presensi'  => $tgl_dari,
                        'status'        => $status,
                        'kode_izin'     => $kode_izin
                    ]);
                    $tgl_dari = date("Y-m-d", strtotime("+1 days", strtotime($tgl_dari)));
                }
            }

            $update = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update(['status_approved' => $status_approved]);
            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }

        //$update = DB::table('pengajuan_izin')
        //    ->where('id', $kode_izin)
        //   ->update(['status_approved' => $status_approved]);

        //if ($update) {
        //    return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        //} else {
        //    return Redirect::back()->with(['warning' => 'Data Gagal Di Update']);
        //}
    }

    
    public function batalkanizinsakit($kode_izin)
    {
        DB::beginTransaction();
        try {
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->update([
            'status_approved' => 0
        ]);
            DB::table('presensi')->where('kode_izin', $kode_izin)->delete();
            DB::commit();
            return Redirect::back()->with(['success' => 'Data Berhasil Di Batalkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning' => 'Data Gagal Di Batalkan']);
        }
    }

    public function cekpengajuanizin(Request $request)
    {
        $tgl_izin = $request->tgl_izin;
        $nik = Auth::guard('karyawan')->user()->nik;

        $cek = DB::table('pengajuan_izin')->where('nik', $nik)->where('tgl_izin', $tgl_izin)->count();
        return $cek;
    }

    public function showact($kode_izin)
    {
        $dataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        return view('presensi.showact', compact('dataizin'));
    }

    public function deleteizin($kode_izin)
    {
        $cekdataizin = DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->first();
        $doc_sid = $cekdataizin->doc_sid;

        try {
            DB::table('pengajuan_izin')->where('kode_izin', $kode_izin)->delete();
            if ($doc_sid != null) {
                Storage::delete('/public/uploads/sid/' . $doc_sid);
            }
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Di Hapus']);
        } catch (\Exception $e) {
            return redirect('/presensi/izin')->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }

}