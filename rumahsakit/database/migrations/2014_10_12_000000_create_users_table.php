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
        // Schema::create('users', function (Blueprint $table) {
        //     $table->string('id')->primary();
        //     $table->string('email')->unique();
        //     $table->string('password');
        //     $table->enum('role', ['admin', 'pegawai', 'atasan']);
        //     $table->string('nama')->nullable();
        //     $table->string('jurusan')->nullable();
        //     $table->string('pendidikan_terakhir')->nullable();
        //     $table->integer('umur')->nullable();
        //     $table->string('tempat_lahir')->nullable();
        //     $table->date('tanggal_lahir')->nullable();
        //     $table->string('no_telepon')->nullable();
        //     $table->string('posisi')->nullable();
        //     $table->string('jabatan')->nullable();
        //     $table->string('statusAkun')->nullable();
        //     $table->boolean('sertifikasi')->default(0);
        //     $table->boolean('ikut_pelatihan')->default(0);

        //     foreach (['b1', 'b2', 'b3', 'b4', 'b5', 'a1', 'a2', 'a3', 'a4', 'a5'] as $col) {
        //         $table->tinyInteger($col)->default(0);
        //     }

        //     $table->decimal('nilai', 5, 2)->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
