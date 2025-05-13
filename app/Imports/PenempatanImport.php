<?php

namespace App\Imports;

use App\Models\Instansi;
use App\Models\Kelas;
use App\Models\Menempati;
use App\Models\Siswa;
use App\Models\TahunAjar;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PenempatanImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public function collection(Collection $rows)
    {
        // dd($rows);

        foreach ($rows as $row) {
            $kelas = Kelas::firstOrCreate(['nama' => $row['kelas']]);
            $tahunAjar = TahunAjar::firstOrCreate(['tahun_ajar' => $row['tahun_ajar']]);

            $instansi = Instansi::firstOrCreate([
                'nama' => $row['nama_instansi'],
            ], [
                'alamat' => $row['alamat'],
                'domisili' => $row['domisili'],
                'latitude' => $row['latitude'],
                'longitude' => $row['longitude'],
            ]);

            $user = User::firstOrCreate([
                'username' => $row['nis']
            ], [
                'nama_lengkap' => $row['nama_siswa'],
                'password' => Str::random(8),
                'role' => 'siswa',
            ]);

            $jenisKelamin = null;
            if (($row['jenis_kelamin']) == 'L') {
                $jenisKelamin = 'Laki-laki';
            } elseif (($row['jenis_kelamin']) == 'P') {
                $jenisKelamin = 'Perempuan';
            }

            $siswa = Siswa::firstOrCreate([
                'nis' => $row['nis']
            ], [
                'user_id' => $user->id,
                'jenis_kelamin' => $jenisKelamin,
                'kelas_id' => $kelas->id,
                'tahun_ajar_id' => $tahunAjar->id,
            ]);

            Menempati::firstOrCreate([
                'siswa_id' => $siswa->id,
                'instansi_id' => $instansi->id,
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
