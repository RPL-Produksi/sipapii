<?php

namespace App\Exports;

use App\Models\Pembimbingan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataPembimbinganExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Pembimbingan::with(['siswa', 'pembimbing', 'guruMapelPKL'])->get()->map(function ($pembimbingan) {
            return [
                'NIS' => $pembimbingan->siswa->nis ?? '-',
                'Nama Siswa' => $pembimbingan->siswa->user->nama_lengkap ?? '-',
                'Nama Guru Pembimbing' => $pembimbingan->pembimbing->user->nama_lengkap ?? '-',
                'Nama Guru Mapel PKL' => $pembimbingan->guruMapelPKL->user->nama_lengkap ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama Siswa',
            'Nama Guru Pembimbing',
            'Nama Guru Mapel PKL',
        ];
    }
}
