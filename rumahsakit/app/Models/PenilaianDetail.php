<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'penilaian_id',
        'T1',
        'T2',
        'Pendidikan',
        'Umur',
        'Sertifikasi',
        'PernahPelatihan',
        'Jurusan',
        'Posisi'
    ];

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class);
    }
}
