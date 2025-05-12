<?php

namespace App\Http\Controllers\Guru\Data;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruAbsensiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('type') == 'data') {
            return view('guru.data.absen.absensi', [], ['menu_type' => 'absen', 'submenu_type' => 'absensi']);
        } else if ($request->query('type') == 'rekap') {
            return view('guru.data.absen.rekap', [], ['menu_type' => 'absen', 'submenu_type' => 'rekap']);
        } else {
            return redirect()->route('guru.dashboard');
        }
    }

    public function data(Request $request)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $guruId = Auth::user()->guru->id;

        $data = Absensi::query()
            ->whereHas('siswa.pembimbingan', function ($query) use ($guruId) {
                $query->where('guru_mapel_pkl_id', $guruId);
            })
            ->with([
                'siswa.user',
                'siswa.kelas',
                'siswa.penempatan.instansi',
                'siswa.pembimbingan.pembimbing.user',
                'siswa.pembimbingan.guruMapelPKL.user'
            ]);

        if ($tanggalAkhir && $tanggalAwal) {
            $tanggalAwal = Carbon::createFromFormat('Y-m-d', $tanggalAwal)->format('d-m-Y');
            $tanggalAkhir = Carbon::createFromFormat('Y-m-d', $tanggalAkhir)->format('d-m-Y');
            $data->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
        }

        $count = $data->count();
        $countFiltered = $count;

        $data = $data->skip($start)->take($length)->get();

        return response()->json([
            "draw" => intval($request->input('draw', 1)),
            "recordsTotal" => $count,
            "recordsFiltered" => $countFiltered,
            "limit" => $length,
            "data" => $data
        ]);
    }

    public function dataById(Request $request, $id)
    {
        $guruId = Auth::user()->guru->id;

        $data = Absensi::query()
            ->whereHas('siswa.pembimbingan', function ($query) use ($guruId) {
                $query->where('guru_mapel_pkl_id', $guruId);
            })
            ->with([
                'siswa.user',
                'siswa.kelas',
                'siswa.penempatan.instansi',
                'siswa.pembimbingan.pembimbing.user',
                'siswa.pembimbingan.guruMapelPKL.user'
            ])
            ->find($id);

        return response()->json($data);
    }
}
