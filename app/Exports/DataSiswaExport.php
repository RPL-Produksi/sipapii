<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataSiswaExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Siswa::with(['user', 'kelas', 'tahunAjar'])->get()->map(function ($siswa) {
            return [
                'NIS' => $siswa->nis ?? '-',
                'Nama' => $siswa->user->nama_lengkap ?? '-',
                'Username' => $siswa->user->username ?? '-',
                'Password' => $siswa->user->password ?? '-',
                'Jenis Kelamin' => $siswa->jenis_kelamin ?? '-',
                'Kelas' => $siswa->kelas->nama ?? '-',
                'Tahun Ajar' => $siswa->tahunAjar->tahun_ajar ?? '-'
            ];
        });
    }

    public function headings(): array
    {
        return ['NIS', 'Nama', 'Username', 'Password', 'Jenis Kelamin', 'Kelas', 'Tahun Ajar'];
    }
}
