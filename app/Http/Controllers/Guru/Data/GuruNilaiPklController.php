<?php

namespace App\Http\Controllers\Guru\Data;

use App\Exports\DataNilaiSiswaExport;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\SiswaNilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

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

    public function getRekomendasi($id)
    {
        $absen = Absensi::where('siswa_id', $id)->get();

        if ($absen->isEmpty()) {
            return response()->json(['success' => false]);
        }

        $absenRated = $absen->whereNotNull('rating_tugas')->whereNotNull('rating_kompetensi');
        $avgTugas = $absenRated->isNotEmpty() ? round($absenRated->avg('rating_tugas'), 2) : 0;
        $avgKompetensi = $absenRated->isNotEmpty() ? round($absenRated->avg('rating_kompetensi'), 2) : 0;

        $jumlahHadir = $absen->where('status', 'Hadir')->count();
        $totalAbsen = $absen->count();
        $persentaseHadir = $totalAbsen > 0 ? round(($jumlahHadir / $totalAbsen) * 100, 2) : 0;

        return response()->json([
            'success' => true,
            'rating_tugas' => $avgTugas,
            'rating_kompetensi' => $avgKompetensi,
            'nilai_soft_skill' => $persentaseHadir,
            'nilai_wirausaha' => $persentaseHadir,
        ]);
    }

    public function exportNilai()
    {
        return Excel::download(new DataNilaiSiswaExport, 'data_nilai_siswa.xlsx');
    }
}
