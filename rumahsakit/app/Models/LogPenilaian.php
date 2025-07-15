<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPenilaian extends Model
{
    use HasFactory;
    protected $primaryKey = 'penilaian_id';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'penilaian_id',
        'user_id',
        'pelatihan_id',
        'tanggal_penilaian',
        'skor',
        'keterangan',
        'detail',
        'created_by'
    ];

    protected $casts = [
        'detail' => 'array',
        'tanggal_penilaian' => 'datetime',
    ];


    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class);
    }
}
