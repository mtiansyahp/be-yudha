<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    use HasFactory;
    public    $incrementing = false;
    protected $keyType = 'int';
    protected $fillable = [
        'id',
        'nama_pelatihan',
        'tanggal',
        'deskripsi',
        'syarat',
        'kualifikasi',
        'pendidikan_terakhir',
        'jurusan',
        'posisi',
        'max_umur',
        'b1',
        'b2',
        'b3',
        'b4',
        'b5',
        'a1',
        'a2',
        'a3',
        'a4',
        'a5',
        'sertifikasi',
        'ikut_pelatihan'
    ];

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'pelatihan_user', 'pelatihan_id', 'user_id');
    }
    
}
