<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjar;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
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

        $jenisKelamin = null;
        if (($row['jenis_kelamin'] ?? $row['Jenis_Kelamin']) == 'L') {
            $jenisKelamin = 'Laki-laki';
        } elseif (($row['jenis_kelamin'] ?? $row['Jenis_Kelamin']) == 'P') {
            $jenisKelamin = 'Perempuan';
        }

        $kelasNama = $row['kelas'] ?? $row['Kelas'];
        $kelas = Kelas::firstOrCreate(['nama' => trim($kelasNama)]);

        $tahunAjarNama = $row['tahun_ajar'] ?? $row['Tahun_Ajar'];
        $tahunAjar = TahunAjar::firstOrCreate(['tahun_ajar' => trim($tahunAjarNama)]);

        if ($user) {
            Siswa::create([
                'user_id' => $user->id,
                'nis' => $row['nis'] ?? $row['NIS'],
                'jenis_kelamin' => $jenisKelamin,
                'kelas_id' => $kelas->id,
                'tahun_ajar_id' => $tahunAjar->id,
            ]);
        }
    }
}
