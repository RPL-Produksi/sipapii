<?php

namespace App\Http\Controllers\Admin\Data\Jurnal;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminJurnalController extends Controller
{
  public function index(Request $request)
  {
    if ($request->query('type') == 'all') {
      $menuType = 'jurnal-data';
    } else if ($request->query('type') == 'belum-validasi') {
      $menuType = 'jurnal-not-validasi-data';
    }

    return view('admin.data.jurnal.index', [], ['menu_type' => 'jurnal', 'submenu_type' => $menuType]);
  }

  public function data(Request $request)
  {
    $length = intval($request->input('length', 15));
    $start = intval($request->input('start', 0));
    $tanggalAwal = $request->input('tanggal_awal');
    $tanggalAkhir = $request->input('tanggal_akhir');

    if ($request->query('type') == 'all') {
      $data = Jurnal::query()->with('siswa.user', 'siswa.kelas', 'guruMapelPkl.user')->orderBy('tanggal', 'desc')->where('validasi', '!=', 'Belum Divalidasi');
      if ($tanggalAkhir && $tanggalAwal) {
        $tanggalAwal = Carbon::createFromFormat('Y-m-d', $tanggalAwal)->format('d-m-Y');
        $tanggalAkhir = Carbon::createFromFormat('Y-m-d', $tanggalAkhir)->format('d-m-Y');
        $data->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
      }
    } else if ($request->query('type') == 'belum-validasi') {
      $data = Jurnal::query()->with('siswa.user', 'siswa.kelas', 'guruMapelPkl.user')->orderBy('tanggal', 'desc')->where('validasi', 'Belum Divalidasi');
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
    if ($request->query('type') == 'all') {
      $data = Jurnal::query()->with('siswa.user', 'siswa.kelas', 'guruMapelPkl.user')->where('validasi', '!=', 'Belum Divalidasi')->find($id);
    } else if ($request->query('type') == 'belum-validasi') {
      $data = Jurnal::query()->with('siswa.user', 'siswa.kelas', 'guruMapelPkl.user')->where('validasi', 'Belum Divalidasi')->find($id);
    } else {
      return response()->json([
        'error' => 'Invalid type'
      ], 400);
    }

    return response()->json($data);
  }

  public function editStatus(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'validasi' => 'required|string',
    ]);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput($request->all());
    }

    $jurnal = Jurnal::find($id);
    if (!$jurnal) {
      return redirect()->back()->with('error', 'Jurnal not found');
    }

    $jurnal->validasi = $request->validasi;
    $jurnal->save();

    return redirect()->back()->with('success', 'Status Jurnal berhasil diubah');
  }
}
