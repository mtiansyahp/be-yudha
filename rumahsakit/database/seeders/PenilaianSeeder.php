<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenilaianSeeder extends Seeder
{
    public function run(): void
    {
        // DB::table('penilaians')->truncate();
        DB::table('penilaians')->insert([
            [
                'id' => '1-1',
                'user_id' => 1, // ← Tambahkan ini
                'skor' => 75,
                'keterangan' => 'Baik',
                'pelatihan_id' => 1
            ],
            [
                'id' => '1-2',
                'user_id' => 2,
                'skor' => 50,
                'keterangan' => 'Kurang',
                'pelatihan_id' => 1
            ],
            [
                'id' => '1-3',
                'user_id' => 3,
                'skor' => 56.46,
                'keterangan' => 'Kurang',
                'pelatihan_id' => 1
            ]
        ]);
    }
}
