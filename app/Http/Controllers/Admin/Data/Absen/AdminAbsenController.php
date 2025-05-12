<?php

namespace App\Http\Controllers\Admin\Data\Absen;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminAbsenController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('type') == 'hari-ini') {
            $menuType = 'absen-hari-ini-data';
        } else if ($request->query('type') == 'all') {
            $menuType = 'absensi-data';
        }

        return view('admin.data.absen.index', [], ['menu_type' => 'absen', 'submenu_type' => $menuType]);
    }

    public function data(Request $request)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        if ($request->query('type') == 'hari-ini') {
            $data = Absen::query()->with('siswa.user', 'siswa.kelas', 'siswa.penempatan.instansi', 'siswa.pembimbingan.pembimbing.user', 'siswa.pembimbingan.guruMapelPKL.user');
        } else if ($request->query('type') == 'all') {
            $data = Absensi::query()->with('siswa.user', 'siswa.kelas', 'siswa.penempatan.instansi', 'siswa.pembimbingan.pembimbing.user', 'siswa.pembimbingan.guruMapelPKL.user');

            if ($tanggalAkhir && $tanggalAwal) {
                $tanggalAwal = Carbon::createFromFormat('Y-m-d', $tanggalAwal)->format('d-m-Y');
                $tanggalAkhir = Carbon::createFromFormat('Y-m-d', $tanggalAkhir)->format('d-m-Y');
                $data->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
            }
        } else {
            return response()->json([
                'error' => 'Invalid type'
            ], 400);
        }

        $count = $data->count();
        $countFiltered = $count;

        $data = $data->skip($start)->take($length)->get();

        $response = [
            "draw" => intval($request->input('draw', 1)),
            "recordsTotal" => $count,
            "recordsFiltered" => $countFiltered,
            "limit" => $length,
            "data" => $data
        ];

        return response()->json($response);
    }

    public function dataById(Request $request, $id)
    {
        if ($request->query('type') == 'hari-ini') {
            $data = Absen::query()->with('siswa.user', 'siswa.kelas', 'siswa.penempatan.instansi', 'siswa.pembimbingan.pembimbing.user', 'siswa.pembimbingan.guruMapelPKL.user')->find($id);
        } else if ($request->query('type') == 'all') {
            $data = Absensi::query()->with('siswa.user', 'siswa.kelas', 'siswa.penempatan.instansi', 'siswa.pembimbingan.pembimbing.user', 'siswa.pembimbingan.guruMapelPKL.user')->find($id);
        }

        return response()->json($data);
    }
}
