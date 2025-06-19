<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\Guru;
use App\Models\Jurnal;
use App\Models\Pembimbingan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $guruUserId = Auth::user()->id;
        $guru = Guru::where('user_id', $guruUserId)->first();
        $tanggal = now()->format('d-m-Y');

        $data['siswaIds'] = Pembimbingan::where('guru_mapel_pkl_id', $guru->id)->pluck('siswa_id');

        $data['totalSiswa'] = $data['siswaIds']->count();

        $data['absenHariIni'] = Absen::whereIn('siswa_id', $data['siswaIds'])
            ->where('tanggal', $tanggal)
            ->get();

        $data['sudahAbsen'] = $data['absenHariIni']->whereNotNull('jam_masuk')->count();

        $data['belumAbsen'] = $data['totalSiswa'] - $data['sudahAbsen'];

        $data['jurnalHariIni'] = Jurnal::whereIn('siswa_id', $data['siswaIds'])
            ->where('tanggal', $tanggal)
            ->count();

        $data['absensi'] = Siswa::whereHas('pembimbingan', function ($query) use ($guruUserId) {
            $query->whereHas('guruMapelPKL', function ($q) use ($guruUserId) {
                $q->where('user_id', $guruUserId);
            });
        })
            ->with(['user', 'absen' => function ($q) use ($tanggal) {
                $q->where('tanggal', $tanggal);
            }])
            ->get();

        return view('guru.dashboard', [], ['menu_type' => 'dashboard'])->with($data);
    }
}
