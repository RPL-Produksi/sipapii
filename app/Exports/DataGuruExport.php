<?php

namespace App\Exports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataGuruExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Guru::with(['user'])->get()->map(function ($guru) {
            return [
                'NIP' => $guru->nip ?? '-',
                'Nama' => $guru->user->nama_lengkap ?? '-',
                'Username' => $guru->user->username ?? '-',
                'Password' => $guru->user->password ?? '-',
                'No HP' => $guru->nomor_wa ?? '-'
            ];
        });
    }

    public function headings(): array
    {
        return ['NIP', 'Nama', 'Username', 'Password', 'No HP'];
    }
}
