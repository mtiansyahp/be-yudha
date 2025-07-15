<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'email',
        'password',
        'role',
        'nama',
        'jurusan',
        'pendidikan_terakhir',
        'umur',
        'tempat_lahir',
        'tanggal_lahir',
        'no_telepon',
        'posisi',
        'jabatan',
        'statusAkun',
        'sertifikasi',
        'ikut_pelatihan',
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
        'nilai'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }

    public function logPenilaians()
    {
        return $this->hasMany(LogPenilaian::class);
    }
    public function pelatihans()
    {
        return $this->belongsToMany(Pelatihan::class, 'pelatihan_user', 'user_id', 'pelatihan_id');
    }
    
}
