<?php

namespace App\Imports;

use App\Models\Instansi;
use App\Models\Kelas;
use App\Models\Menempati;
use App\Models\Siswa;
use App\Models\TahunAjar;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PenempatanImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $rowIndex => $row) {
            if (!isset($row['kelas']) || empty(trim($row['kelas']))) {
                Log::warning("Baris ke-" . ($rowIndex + 2) . " dilewati: Kolom 'kelas' kosong atau tidak ditemukan.", ['row_data' => $row]);
                continue;
            }

            if (!isset($row['tahun_ajar']) || empty(trim($row['tahun_ajar']))) {
                Log::warning("Baris ke-" . ($rowIndex + 2) . " dilewati: Kolom 'tahun_ajar' kosong atau tidak ditemukan.", ['row_data' => $row]);
                continue;
            }

            if (!isset($row['nama_instansi']) || empty(trim($row['nama_instansi']))) {
                Log::warning("Baris ke-" . ($rowIndex + 2) . " dilewati: Kolom 'nama_instansi' kosong atau tidak ditemukan.", ['row_data' => $row]);
                continue;
            }

            if (!isset($row['nis']) || empty(trim($row['nis']))) {
                Log::warning("Baris ke-" . ($rowIndex + 2) . " dilewati: Kolom 'nis' kosong atau tidak ditemukan.", ['row_data' => $row]);
                continue;
            }

            if (!isset($row['nama_siswa']) || empty(trim($row['nama_siswa']))) {
                Log::warning("Baris ke-" . ($rowIndex + 2) . " dilewati: Kolom 'nama_siswa' kosong atau tidak ditemukan.", ['row_data' => $row]);
                continue;
            }

            $tahunAjar = TahunAjar::firstOrCreate(
                ['tahun_ajar' => trim($row['tahun_ajar'])]
            );

            $kelas = Kelas::firstOrCreate([
                'nama' => trim($row['kelas']),
                'tahun_ajar_id' => $tahunAjar->id
            ]);

            $instansi = Instansi::firstOrCreate(
                [
                    'nama' => trim($row['nama_instansi']),
                ],
                [
                    'alamat' => isset($row['alamat']) ? trim($row['alamat']) : null,
                    'domisili' => isset($row['domisili']) ? trim($row['domisili']) : null,
                    'latitude' => isset($row['latitude']) ? trim($row['latitude']) : null,
                    'longitude' => isset($row['longitude']) ? trim($row['longitude']) : null,
                ]
            );

            $user = User::firstOrCreate(
                [
                    'username' => trim($row['nis'])
                ],
                [
                    'nama_lengkap' => trim($row['nama_siswa']),
                    'password' => mt_rand(10000000, 99999999),
                    'role' => 'siswa',
                ]
            );

            $jenisKelamin = null;
            if (isset($row['jenis_kelamin'])) {
                $jkValue = strtoupper(trim($row['jenis_kelamin']));
                if ($jkValue == 'L') {
                    $jenisKelamin = 'Laki-laki';
                } elseif ($jkValue == 'P') {
                    $jenisKelamin = 'Perempuan';
                } else {
                    Log::info("Nilai 'jenis_kelamin' tidak valid pada baris ke-" . ($rowIndex + 2) . ": " . $row['jenis_kelamin'], ['row_data' => $row]);
                }
            } else {
                Log::info("Kolom 'jenis_kelamin' tidak ditemukan atau kosong pada baris ke-" . ($rowIndex + 2), ['row_data' => $row]);
            }


            $siswa = Siswa::firstOrCreate(
                [
                    'nis' => trim($row['nis'])
                ],
                [
                    'user_id' => $user->id,
                    'jenis_kelamin' => $jenisKelamin,
                    'kelas_id' => $kelas->id,
                    'tahun_ajar_id' => $tahunAjar->id,
                ]
            );

            Menempati::firstOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'instansi_id' => $instansi->id,
                ]
            );
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
