<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SiswaNilai extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'siswa_id',
        'nilai1',
        'nilai2',
        'nilai3',
        'nilai4',
        'guru_mapel_pkl_id',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }
}
