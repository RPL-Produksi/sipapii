<?php

namespace App\Http\Controllers\Guru\Data;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruSiswaController extends Controller
{
    public function index()
    {
        $guruUserId = Auth::user()->guru->id;

        $data['siswa'] = Siswa::whereHas('pembimbingan', function ($query) use ($guruUserId) {
            $query->where('guru_mapel_pkl_id', $guruUserId);
        })
            ->with('user', 'kelas', 'pembimbingan', 'penempatan')
            ->get();

        return view('guru.data.siswa.index', [], ['menu_type' => 'siswa-data'])->with($data);
    }

    public function dataById($id)
    {
        $guruUserId = Auth::user()->guru->id;

        $siswa = Siswa::whereHas('pembimbingan', function ($query) use ($guruUserId) {
            $query->where('guru_mapel_pkl_id', $guruUserId);
        })
            ->with('user', 'kelas', 'pembimbingan.pembimbing.user', 'penempatan.instansi')
            ->where('id', $id)
            ->first();

        return response()->json($siswa);
    }
}
