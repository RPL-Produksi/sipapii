<?php

namespace App\Console\Commands;

use App\Models\Absen;
use App\Models\Absensi;
use App\Models\Jurnal;
use App\Models\Menempati;
use App\Models\Pembimbingan;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RekapAbsen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rekap-absen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memindahkan data absen ke absensi dan menambahkan status Alpa jika siswa belum absen';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->format('d-m-Y');
        $absenHariIni = Absen::where('tanggal', $today)->get();
        $siswaIds = Siswa::pluck('id');

        $siswaBelumAbsen = $siswaIds->diff($absenHariIni->pluck('siswa_id'));
        foreach ($siswaBelumAbsen as $siswaId) {
            $menempati = Menempati::where('siswa_id', $siswaId)->first();
            if (!$menempati) {
                continue;
            }

            $absenHariIni->push(
                new Absen([
                    'siswa_id' => $siswaId,
                    'tanggal' => $today,
                    'status' => 'Alpa',
                ])
            );

            $pembimbingan = Pembimbingan::where('siswa_id', $siswaId)->first();

            Jurnal::create([
                'siswa_id' => $siswaId,
                'guru_mapel_pkl_id' => $pembimbingan->guru_mapel_pkl_id,
                'tanggal' => $today,
                'deskripsi_jurnal' => null,
                'validasi' => 'Tidak Mengisi'
            ]);
        }

        foreach ($absenHariIni as $absen) {
            $menempati = Menempati::where('siswa_id', $absen->siswa_id)->first();

            if (!$menempati) {
                continue;
            }

            Absensi::create([
                'siswa_id' => $absen->siswa_id,
                'tanggal' => $absen->tanggal,
                'latitude' => $absen->latitude,
                'longitude' => $absen->longitude,
                'jam_masuk' => $absen->jam_masuk,
                'jam_pulang' => $absen->jam_pulang ?: '00:00',
                'status' => $absen->status,
                'alasan' => $absen->alasan,
                'foto_masuk' => $absen->foto_masuk,
                'foto_pulang' => $absen->foto_pulang,
                'jarak' => $absen->jarak,
            ]);

            if ($absen->jam_masuk && !$absen->jam_pulang) {
                $pembimbingan = Pembimbingan::where('siswa_id', $absen->siswa_id)->first();

                Jurnal::create([
                    'siswa_id' => $absen->siswa_id,
                    'guru_mapel_pkl_id' => $pembimbingan->guru_mapel_pkl_id,
                    'tanggal' => $absen->tanggal,
                    'deskripsi_jurnal' => null,
                    'validasi' => 'Tidak Mengisi'
                ]);
            }
        }

        Absen::where('tanggal', $today)->delete();
        $this->info(now() . ' Data absen telah dipindahkan ke tabel absensi');
    }
}
