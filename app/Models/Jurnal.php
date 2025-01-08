<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasUuids;

    protected $fillable = [
        'siswa_id',
        'guru_mapel_pkl_id',
        'tanggal',
        'kegiatan',
        'deskripsi_jurnal',
        'validasi',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }
}
