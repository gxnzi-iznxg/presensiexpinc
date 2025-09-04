<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {   
        $users = DB::table('users')
            ->select('id', 'name', 'email')
            ->get();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $nama_user = $request->nama_user;
        $email = $request->email;
        $password = bcrypt($request->password);
        
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $nama_user,
                'email' => $email,
                'password' => $password
            ]);

            DB::commit();
            
            return Redirect::back()->with(['success'=>'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning'=>'Data Gagal Disimpan']);
        }
    }

    public function edit(Request $request)
    {
        $id_user = $request->id_user;
        $user = DB::table('users')->where('id', $id_user)->first();
        return view('konfigurasi.edituser', compact('user'));
    }

    public function update(Request $request, $id_user)
    {
        $nama_user = $request->nama_user;
        $email = $request->email;
        $password = bcrypt($request->password);

        if(isset($request->password)) {
            $data = [
                'name' => $nama_user,
                'email' => $email,
                'password' => $password
            ];
        } else {
            $data = [
                'name' => $nama_user,
                'email' => $email,
            ];
        }

        DB::beginTransaction();
        try {
            DB::table('users')->where('id', $id_user)->update($data);

            DB::commit();
            return Redirect::back()->with(['success'=>'Data Berhasil Di Update']);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(['warning'=>'Data Gagal Di Update']);
        }
    }

    public function delete($id_user)
    {
        try {
            DB::table('users')->where('id', $id_user)->delete();
            return Redirect::back()->with(['success'=>'Data Berhasil Di Hapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning'=>'Data Gagal Di Hapus']);
        }
    }
}
