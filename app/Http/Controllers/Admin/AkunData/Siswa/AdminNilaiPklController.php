<?php

namespace App\Http\Controllers\Admin\AkunData\Siswa;

use App\Exports\DataNilaiSiswaExport;
use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\SiswaNilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AdminNilaiPklController extends Controller
{
    public function index()
    {
        $data['nilai'] = SiswaNilai::with('siswa.user', 'siswa.kelas')
            ->orderBy('created_at', 'desc')
            ->get();

        $data['siswa'] = Siswa::with('user', 'kelas', 'pembimbingan')
            ->get();

        return view('admin.akun-data.siswa.nilai-siswa.index', [], ['menu_type' => 'nilai-data'])->with($data);
    }

    public function store(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswas,id',
            'nilai1' => 'nullable|numeric|min:0|max:100',
            'nilai2' => 'nullable|numeric|min:0|max:100',
            'nilai3' => 'nullable|numeric|min:0|max:100',
            'nilai4' => 'nullable|numeric|min:0|max:100',
            'guru_pkl_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        SiswaNilai::updateOrCreate(
            ['id' => @$id],
            [
                'siswa_id' => $request->siswa_id,
                'nilai1' => $request->nilai1,
                'nilai2' => $request->nilai2,
                'nilai3' => $request->nilai3,
                'nilai4' => $request->nilai4,
                'guru_mapel_pkl_id' => $request->guru_pkl_id,
            ]
        );

        return redirect()->back()->with('success', 'Data Nilai Berhasil Disimpan');
    }

    public function dataById($id)
    {
        $nilai = SiswaNilai::with('siswa.user', 'siswa.kelas')
            ->orderBy('created_at', 'desc')
            ->where('id', $id)
            ->first();

        return response()->json($nilai);
    }

    public function exportNilai()
    {
        return Excel::download(new DataNilaiSiswaExport, 'data_nilai_siswa.xlsx');
    }
}
