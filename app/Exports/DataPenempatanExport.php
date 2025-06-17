<?php

namespace App\Exports;

use App\Models\Menempati;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataPenempatanExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Menempati::with(['siswa', 'instansi'])->get()->map(function ($menempati) {
            return [
                'Nama Instansi' => $menempati->instansi->nama ?? '-',
                'Alamat Instansi' => $menempati->instansi->alamat ?? '-',
                'NIS' => $menempati->siswa->nis ?? '-',
                'Nama' => $menempati->siswa->user->nama_lengkap ?? '-',
                'Jenis Kelamin' => $menempati->siswa->jenis_kelamin ?? '-',
                'Kelas' => $menempati->siswa->kelas->nama ?? '-',
                'Nama Pembimbing' => $menempati->siswa->pembimbingan->pembimbing->user->nama_lengkap ?? '-',
                'Nama Guru Mapel PKL' => $menempati->siswa->pembimbingan->guruMapelPKL->user->nama_lengkap ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Instansi',
            'Alamat Instansi',
            'NIS',
            'Nama',
            'Jenis Kelamin',
            'Kelas',
            'Nama Pembimbing',
            'Nama Guru Mapel PKL',
        ];
    }
}
