<?php

namespace App\Http\Controllers\Guru\Data;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\SiswaNilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GuruNilaiPklController extends Controller
{
    public function index()
    {
        $guruUserId = Auth::user()->guru->id;

        $data['nilai'] = SiswaNilai::where('guru_mapel_pkl_id', $guruUserId)
            ->with('siswa.user', 'siswa.kelas')
            ->orderBy('created_at', 'desc')
            ->get();

        $data['siswa'] = Siswa::whereHas('pembimbingan', function ($query) use ($guruUserId) {
            $query->where('guru_mapel_pkl_id', $guruUserId);
        })
            ->with('user', 'kelas', 'pembimbingan')
            ->get();

        return view('guru.data.nilai.index', [], ['menu_type' => 'nilai-siswa'])->with($data);
    }

    public function store(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswas,id',
            'nilai1' => 'nullable|numeric|min:0|max:100',
            'nilai2' => 'nullable|numeric|min:0|max:100',
            'nilai3' => 'nullable|numeric|min:0|max:100',
            'nilai4' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
        }

        $guruUserId = Auth::user()->guru->id;
        SiswaNilai::updateOrCreate(
            ['id' => @$id],
            [
                'siswa_id' => $request->siswa_id,
                'nilai1' => $request->nilai1,
                'nilai2' => $request->nilai2,
                'nilai3' => $request->nilai3,
                'nilai4' => $request->nilai4,
                'guru_mapel_pkl_id' => $guruUserId,
            ]
        );

        return redirect()->back()->with('success', 'Data Nilai Berhasil Disimpan');
    }

    public function dataById($id)
    {
        $guruUserId = Auth::user()->guru->id;

        $nilai = SiswaNilai::where('guru_mapel_pkl_id', $guruUserId)
            ->with('siswa.user', 'siswa.kelas')
            ->orderBy('created_at', 'desc')
            ->where('id', $id)
            ->first();

        return response()->json($nilai);
    }
}
