<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasUuids;

    protected $fillable = [
        'id',
        'user_id',
        'kelas_id',
        'nomor_wa',
        'jenis_kelamin',
        'tahun_ajar_id',
    ];
}
