<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SiswaJurnalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jurnal::orderBy('created_at', 'DESC')
            ->where('siswa_id', Auth::user()->siswa->id);

        if ($request->has('tanggal_awal') && $request->has('tanggal_akhir')) {
            $tanggalAwal = Carbon::parse($request->tanggal_awal)->format('d-m-Y');
            $tanggalAkhir = Carbon::parse($request->tanggal_akhir)->format('d-m-Y');

            $query->whereBetween('tanggal', [
                $tanggalAwal,
                $tanggalAkhir,
            ]);
        }

        $data['jurnal'] = $query->paginate(10);

        $data['jurnal']->getCollection()->transform(function ($jurnal) {
            $jurnal->tanggal = Carbon::parse($jurnal->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY');
            return $jurnal;
        });

        return view('siswa.jurnal', [], ['menu_type' => 'jurnal'])->with($data);
    }

    public function editJurnal(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'deskripsi_jurnal' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $jurnal = Jurnal::where('id', $id)->where('siswa_id', Auth::user()->siswa->id)->first();
        if ($jurnal->validasi == 'Divalidasi') {
            return redirect()->back()->with('error', 'Jurnal sudah divalidasi');
        } else if ($jurnal->validasi == 'Tidak Mengisi') {
            return redirect()->back()->with('error', 'Anda tidak mengisi pada tangal ' . $jurnal->tanggal);
        }

        $jurnal->deskripsi_jurnal = $request->deskripsi_jurnal;
        $jurnal->save();

        return redirect()->back()->with('success', 'Jurnal berhasil diubah');
    }

    public function dataById($id)
    {
        $jurnal = Jurnal::find($id);

        return response()->json($jurnal);
    }
}
