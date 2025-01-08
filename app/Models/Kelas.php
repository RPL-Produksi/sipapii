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
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id', 'id');
    }
}
