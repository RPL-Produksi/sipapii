<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menempati;
use App\Models\Siswa;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $domisilis = ['Luar Kota', 'Dalam Kota'];
        foreach ($domisilis as $domisili) {
            $data['pkl' . str_replace(' ', '', $domisili)] = Siswa::whereHas('penempatan', function ($q) use ($domisili) {
                $q->whereHas('instansi', function ($q) use ($domisili) {
                    $q->where('domisili', $domisili);
                });
            })->count();
        }

        $data['belumDitempatkan'] = Siswa::whereDoesntHave('penempatan')->count();
        $data['siswa'] = Siswa::count();

        $data['absen'] = Siswa::whereHas('absen', function ($q) {
            $q->where('tanggal', now()->format('d-m-Y'));
        })->with('penempatan.instansi')->limit(5)->get();

        $data['penempatan'] = Menempati::orderBy('created_at', 'desc')->with(['siswa.user', 'instansi'])->limit(5)->get();

        // dd($data['penempatan']);

        return view('admin.dashboard', [], ['menu_type' => 'dashboard'])->with($data);
    }
}
