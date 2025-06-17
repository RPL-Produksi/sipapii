<?php

namespace App\Exports;

use App\Models\Instansi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataInstansiExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Instansi::all()->map(function ($instansi) {
            return [
                'Instansi' => $instansi->nama ?? '-',
                'Alamat' => $instansi->alamat ?? '-',
                'Domisili' => $instansi->domisili ?? '-',
                'Latitude' => $instansi->latitude ?? '-',
                'Longitude' => $instansi->longitude ?? '-'
            ];
        });
    }

    public function headings(): array
    {
        return ['Instansi', 'Alamat', 'Domisili', 'Latitude', 'Longitude'];
    }
}
