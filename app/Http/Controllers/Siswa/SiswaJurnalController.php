<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Jurnal;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SiswaJurnalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jurnal::orderBy('tanggal', 'DESC')
            ->where('siswa_id', Auth::user()->siswa->id);

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [
                $request->tanggal_awal,
                $request->tanggal_akhir,
            ]);
        }

        $data['jurnal'] = $query->paginate(16)->withQueryString();
        return view('siswa.jurnal', ['menu_type' => 'jurnal'] + $data);
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

    public function exportJurnal(Request $request)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        $instruktur = $request->input('instruktur') ?? '______________';

        $jurnal = Jurnal::where('siswa_id', $siswa->id)
            ->orderBy('tanggal', 'asc')
            ->get()
            ->groupBy(fn($item) => Carbon::parse($item->tanggal)->translatedFormat('F Y'));

        $pdf = Pdf::loadView('siswa.export.jurnal', [
            'siswa' => $siswa,
            'user' => $user,
            'instruktur' => $instruktur,
            'jurnal' => $jurnal,
        ])->setPaper('A4', 'portrait');

        return $pdf->download('jurnal-' . $user->nama_lengkap . '.pdf');
    }
}
