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
        // Schema::create('penilaian_details', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('penilaian_id');
        //     $table->float('T1');
        //     $table->float('T2');
        //     $table->float('Pendidikan');
        //     $table->float('Umur');
        //     $table->float('Sertifikasi');
        //     $table->float('PernahPelatihan');
        //     $table->float('Jurusan');
        //     $table->float('Posisi');
        //     $table->timestamps();

        //     $table->foreign('penilaian_id')->references('id')->on('penilaians')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_details');
    }
};
