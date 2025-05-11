<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    use HasUuids;

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'latitude',
        'longitude',
        'jam_masuk',
        'jam_pulang',
        'status',
        'alasan',
        'foto_masuk',
        'foto_pulang',
        'jarak',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
