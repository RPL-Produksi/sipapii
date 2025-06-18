<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\TahunAjar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class KelasImport implements ToModel, WithHeadingRow
{
    protected $tahun_ajar_id;

    public function __construct($id)
    {
        $this->tahun_ajar_id = $id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $kelasNama = $row['nama'] ?? $row['Nama'];

        return Kelas::firstOrCreate([
            'nama' => trim($kelasNama),
            'tahun_ajar_id' => $this->tahun_ajar_id,
        ]);
    }
}
