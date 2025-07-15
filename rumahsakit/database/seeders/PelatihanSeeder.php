<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelatihanSeeder extends Seeder
{
    public function run(): void
    {
        // DB::table('pelatihans')->truncate();
        DB::table('pelatihans')->insert([
            [
                'id' => 1,
                'nama_pelatihan' => 'Pelatihan Dasar IGD',
                'tanggal' => '2024-05-10',
                'deskripsi' => 'Pelatihan dasar untuk tenaga IGD',
                'syarat' => 'Lulusan S1',
                'kualifikasi' => 'Perawat',
                'b1' => 1,
                'b2' => 1,
                'b3' => 1,
                'b4' => 1,
                'b5' => 1,
                'a1' => 1,
                'a2' => 0,
                'a3' => 1,
                'a4' => 0,
                'a5' => 1,
                'sertifikasi' => 1,
                'ikut_pelatihan' => 1,
                'pendidikan_terakhir' => 'SMA',
                'jurusan' => 'Keperawatan',
                'posisi' => 'Perawat IGD',
                'max_umur' => 32
            ],
            [
                'id' => 2,
                'nama_pelatihan' => 'Manajemen Obat',
                'tanggal' => '2024-06-01',
                'deskripsi' => 'Pelatihan pengelolaan dan distribusi obat',
                'syarat' => 'Pengalaman 1 tahun',
                'kualifikasi' => 'Apoteker',
                'b1' => 1,
                'b2' => 1,
                'b3' => 1,
                'b4' => 1,
                'b5' => 1,
                'a1' => 0,
                'a2' => 1,
                'a3' => 0,
                'a4' => 1,
                'a5' => 1,
                'sertifikasi' => 0,
                'ikut_pelatihan' => 1,
                'pendidikan_terakhir' => 'SMA',
                'jurusan' => 'Farmasi',
                'posisi' => 'Apoteker',
                'max_umur' => 29
            ],
            [
                'id' => 3,
                'nama_pelatihan' => 'Kesehatan Ibu dan Anak',
                'tanggal' => '2024-07-15',
                'deskripsi' => 'Pelatihan kebidanan untuk tenaga medis',
                'syarat' => 'Lulusan S1',
                'kualifikasi' => 'Bidan',
                'b1' => 1,
                'b2' => 1,
                'b3' => 1,
                'b4' => 1,
                'b5' => 1,
                'a1' => 1,
                'a2' => 0,
                'a3' => 0,
                'a4' => 1,
                'a5' => 1,
                'sertifikasi' => 1,
                'ikut_pelatihan' => 1,
                'pendidikan_terakhir' => 'S1',
                'jurusan' => 'Kebidanan',
                'posisi' => 'Bidan Rawat Inap',
                'max_umur' => 35
            ]
        ]);
    }
}
