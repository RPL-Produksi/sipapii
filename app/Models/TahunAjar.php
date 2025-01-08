<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TahunAjar extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'tahun_ajar',
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'tahun_ajar_id', 'id');
    }
}
