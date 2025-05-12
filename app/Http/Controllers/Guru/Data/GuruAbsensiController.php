<?php

namespace App\Http\Controllers\Guru\Data;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Pembimbingan;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $guruUserId = Auth::user()->guru->id;

        if ($request->query('type') == 'data') {
            return view('guru.data.absen.absensi', [], ['menu_type' => 'absen', 'submenu_type' => 'absensi']);
        } else if ($request->query('type') == 'rekap') {
            $siswas = Siswa::whereHas('pembimbingan', function ($query) use ($guruUserId) {
                $query->where('pembimbing_id', $guruUserId);
            })
                ->with(['user', 'kelas', 'absensi' => function ($q) {
                    $q->select('siswa_id', 'status')
                        ->selectRaw('COUNT(*) as total')
                        ->groupBy('siswa_id', 'status');
                }])
                ->get();

            $data['rekap'] = $siswas->map(function ($siswa) {
                $statusCounts = [
                    'Hadir' => 0,
                    'Izin' => 0,
                    'Sakit' => 0,
                    'Alpa' => 0,
                ];

                foreach ($siswa->absensi as $absen) {
                    $statusCounts[$absen->status] = $absen->total;
                }

                return [
                    'nama'  => $siswa->user->nama_lengkap,
                    'kelas' => $siswa->kelas->nama,
                    'hadir' => $statusCounts['Hadir'],
                    'izin'  => $statusCounts['Izin'],
                    'sakit' => $statusCounts['Sakit'],
                    'alpa'  => $statusCounts['Alpa'],
                ];
            });

            return view('guru.data.absen.rekap', [], ['menu_type' => 'absen', 'submenu_type' => 'rekap'])->with($data);
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
