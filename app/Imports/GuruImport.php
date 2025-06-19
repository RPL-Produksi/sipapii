<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class GuruImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $namaDepan = strtoupper(substr($row['nama_lengkap'] ?? $row['Nama_Lengkap'], 0, 3));
        $nomorWa = preg_replace('/\D/', '', $row['nomor_whatsapp' ?? $row['Nomor_Whatsapp']]);
        $digitTerakhirWa = substr($nomorWa, -2);
        $username = $namaDepan . $digitTerakhirWa;

        $counter = 1;
        $originalUsername = $username;
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        $user = User::create([
            'nama_lengkap' => $row['nama_lengkap'] ?? $row['Nama_Lengkap'],
            'username' => $username,
            'password' => mt_rand(10000000, 99999999),
            'role' => 'guru'
        ]);

        if ($user) {
            Guru::create([
                'nip' => $row['nip'] ?? $row['NIP'],
                'user_id' => $user->id,
                'nomor_wa' => $nomorWa,
            ]);
        }
    }
}
