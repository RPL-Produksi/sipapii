<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\Absensi;
use App\Models\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $data['siswa'] = Auth::user();
        $data['absen'] = Absen::where('siswa_id', Auth::user()->siswa->id)->where('tanggal', Carbon::now()->format('d-m-Y'))->first();
        $data['jurnal'] = Jurnal::orderBy('created_at', 'desc')->where('siswa_id', Auth::user()->siswa->id)->limit(9)->get()->map(function ($jurnal) {
            $jurnal->tanggal = Carbon::parse($jurnal->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY');
            return $jurnal;
        });

        $statusCounts = Absensi::where('siswa_id', Auth::user()->siswa->id)->get()->groupBy('status')->map(function ($group) {
            return $group->count();
        });

        $data['status_counts'] = [
            'Hadir' => $statusCounts->get('Hadir', 0),
            'Sakit' => $statusCounts->get('Sakit', 0),
            'Izin' => $statusCounts->get('Izin', 0),
            'Alpa' => $statusCounts->get('Alpa', 0),
        ];

        return view('siswa.dashboard', [], ['menu_type' => 'dashboard'])->with($data);
    }
}
