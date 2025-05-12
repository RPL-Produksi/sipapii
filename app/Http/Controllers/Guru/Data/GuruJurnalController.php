<?php

namespace App\Http\Controllers\Guru\Data;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GuruJurnalController extends Controller
{
    public function index()
    {
        $guruUserId = Auth::user()->guru->id;
        $data['jurnalNotValidasiCount'] = Jurnal::where('guru_mapel_pkl_id', $guruUserId)
            ->where('validasi', 'Belum Divalidasi')->count();

        return view('guru.data.jurnal.index', [], ['menu_type' => 'jurnal'])->with($data);
    }

    public function data(Request $request)
    {
        $length = intval($request->input('length', 15));
        $start = intval($request->input('start', 0));
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $guruUserId = Auth::user()->guru->id;

        $data = Jurnal::query()
            ->with(
                'siswa.user',
                'siswa.kelas',
                'siswa.penempatan.instansi',
                'siswa.pembimbingan.pembimbing.user'
            )
            ->orderBy('tanggal', 'desc')
            ->where('guru_mapel_pkl_id', $guruUserId);

        if ($tanggalAwal && $tanggalAkhir) {
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

    public function dataById($id)
    {
        $data = Jurnal::with(
            'siswa.user',
            'siswa.kelas',
            'siswa.penempatan.instansi',
            'siswa.pembimbingan.pembimbing.user'
        )->find($id);

        return response()->json($data);
    }

    public function checkJurnal(Request $request, $id)
    {
        $status = $request->query('status');

        $jurnal = Jurnal::find($id);
        if ($status == 'validasi') {
            $jurnal->validasi = 'Divalidasi';
            $jurnal->save();
        } elseif ($status == 'ditolak') {
            $jurnal->validasi = 'Ditolak';
            $jurnal->save();
        }

        return redirect()->back()->with('success', 'Jurnal berhasil diperbarui');
    }
}
