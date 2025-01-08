<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public $userId;
    public $kelasId;
    public $tahunAjaranId;

    public function __construct($kelasId, $tahunAjaranId)
    {
        $this->kelasId = $kelasId;
        $this->tahunAjaranId = $tahunAjaranId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $user = User::create([
            'nama_lengkap' => $row['nama_lengkap'] ?? $row['Nama_Lengkap'],
            'username' => $row['nis'] ?? $row['NIS'],
            'password' => Str::random(8),
            'role' => 'siswa',
        ]);

        if ($row['jenis_kelamin'] == 'L') {
            $jenisKelamin = 'Laki-laki';
        } else if ($row['jenis_kelamin'] == 'P') {
            $jenisKelamin = 'Perempuan';
        }

        if ($user) {
            Siswa::create([
                'user_id' => $user->id,
                'nis' => $row['nis'] ?? $row['NIS'],
                'jenis_kelamin' => $jenisKelamin,
                'kelas_id' => $this->kelasId,
                'tahun_ajar_id' => $this->tahunAjaranId,
            ]);
        }
    }
}
