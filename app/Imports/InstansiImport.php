<?php

namespace App\Imports;

use App\Models\Instansi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InstansiImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Instansi([
            'nama' => $row['nama'] ?? $row['Nama'],
            'alamat' => $row['alamat'] ?? $row['Alamat'],
            'domisili' => $row['domisili'] ?? $row['Domisili'],
            'latitude' => $row['latitude'] ?? $row['Latitude'],
            'longitude' => $row['longitude'] ?? $row['Longitude'],
        ]);
    }
}
