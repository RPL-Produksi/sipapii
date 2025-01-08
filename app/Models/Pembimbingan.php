<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Pembimbingan extends Model
{
    use HasUuids;

    protected $fillable = [
        'siswa_id',
        'pembimbing_id',
        'guru_mapel_pkl_id',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }

    public function pembimbing()
    {
        return $this->belongsTo(Guru::class, 'pembimbing_id', 'id');
    }

    public function guruMapelPKL()
    {
        return $this->belongsTo(Guru::class, 'guru_mapel_pkl_id', 'id');
    }
}
