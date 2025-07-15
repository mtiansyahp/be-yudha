<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogPenilaianSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('log_penilaians')->truncate();

        DB::table('log_penilaians')->insert([
            [
                'penilaian_id' => '1-1',
                'user_id' => '1',
                'pelatihan_id' => '1',
                'tanggal_penilaian' => now(),
                'skor' => 75,
                'keterangan' => 'Baik',
                'detail' => json_encode([
                    "T1" => 1,
                    "T2" => 1,
                    "Pendidikan" => 0,
                    "Umur" => 1,
                    "Sertifikasi" => 1,
                    "PernahPelatihan" => 0,
                    "Jurusan" => 1,
                    "Posisi" => 1
                ])
            ],
            [
                'penilaian_id' => '1-2',
                'user_id' => '2',
                'pelatihan_id' => '1',
                'tanggal_penilaian' => now(),
                'skor' => 50,
                'keterangan' => 'Kurang',
                'detail' => json_encode([
                    "T1" => 1,
                    "T2" => 0,
                    "Pendidikan" => 0,
                    "Umur" => 1,
                    "Sertifikasi" => 1,
                    "PernahPelatihan" => 1,
                    "Jurusan" => 0,
                    "Posisi" => 0
                ])
            ],
            [
                'penilaian_id' => '1-3',
                'user_id' => '3',
                'pelatihan_id' => '1',
                'tanggal_penilaian' => now(),
                'skor' => 56.46,
                'keterangan' => 'Kurang',
                'detail' => json_encode([
                    "T1" => 1,
                    "T2" => 0.6667,
                    "Pendidikan" => 0,
                    "Umur" => 0.85,
                    "Sertifikasi" => 1,
                    "PernahPelatihan" => 1,
                    "Jurusan" => 0,
                    "Posisi" => 0
                ])
            ]
        ]);
    }
}
