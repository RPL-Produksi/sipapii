<?php

namespace App\Exports;

use App\Models\Siswa;
use App\Models\SiswaNilai;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataNilaiSiswaExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user();

        if ($user->role === 'guru') {
            $guruMapelPklId = $user->guru->id;

            $data = Siswa::with(['kelas', 'pembimbingan', 'nilai', 'user'])->whereHas('pembimbingan', function ($q) use ($guruMapelPklId) {
                $q->where('guru_mapel_pkl_id', $guruMapelPklId);
            })->get();
        } else {
            $data = Siswa::with(['kelas', 'pembimbingan', 'nilai', 'user'])->get();
        }

        return $data->map(function ($siswa) {
            return [
                'Nama' => $siswa->user->nama_lengkap ?? '-',
                'Kelas' => $siswa->kelas->nama ?? '-',
                'Internalisasi dan Penerapan Soft Skill' => $siswa->nilai->nilai1 ?? 'Belum Dinilai',
                'Penerapan Hard Skills' => $siswa->nilai->nilai2 ?? 'Belum Dinilai',
                'Peningkatan dan Pengembangan Hard Skills' => $siswa->nilai->nilai3 ?? 'Belum Dinilai',
                'Penyiapan Kemandirian Berwirausaha' => $siswa->nilai->nilai4 ?? 'Belum Dinilai',
                'Guru Mapel PKL' => $siswa->pembimbingan->guruMapelPKL->user->nama_lengkap ?? '-'
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Kelas',
            'Internalisasi dan Penerapan Soft Skill',
            'Penerapan Hard Skills',
            'Peningkatan dan Pengembangan Hard Skills',
            'Penyiapan Kemandirian Berwirausaha',
            'Guru Mapel PKL'
        ];
    }
}
