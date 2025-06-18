<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'nama',
        'tahun_ajar_id'
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id', 'id');
    }

    public function tahunAjar()
    {
        return $this->belongsTo(TahunAjar::class, 'tahun_ajar_id', 'id');
    }
}
