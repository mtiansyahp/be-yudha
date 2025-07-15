<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('pelatihans', function (Blueprint $table) {
        //     $table->string('id')->primary();
        //     $table->string('nama_pelatihan');
        //     $table->date('tanggal');
        //     $table->text('deskripsi')->nullable();
        //     $table->string('syarat')->nullable();
        //     $table->string('kualifikasi')->nullable();
        //     $table->string('pendidikan_terakhir')->nullable();
        //     $table->string('jurusan')->nullable();
        //     $table->string('posisi')->nullable();
        //     $table->integer('max_umur')->nullable();

        //     $table->tinyInteger('b1')->default(0);
        //     $table->tinyInteger('b2')->default(0);
        //     $table->tinyInteger('b3')->default(0);
        //     $table->tinyInteger('b4')->default(0);
        //     $table->tinyInteger('b5')->default(0);

        //     $table->tinyInteger('a1')->default(0);
        //     $table->tinyInteger('a2')->default(0);
        //     $table->tinyInteger('a3')->default(0);
        //     $table->tinyInteger('a4')->default(0);
        //     $table->tinyInteger('a5')->default(0);

        //     $table->boolean('sertifikasi')->default(0);
        //     $table->boolean('ikut_pelatihan')->default(0);

        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelatihans');
    }
};
