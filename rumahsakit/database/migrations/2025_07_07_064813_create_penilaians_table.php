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
        // Schema::create('penilaians', function (Blueprint $table) {
        //     $table->string('id')->primary();
        //     $table->string('user_id');
        //     $table->string('pelatihan_id');
        //     $table->decimal('skor', 5, 2);
        //     $table->string('keterangan')->nullable();
        //     $table->timestamps();

        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        //     $table->foreign('pelatihan_id')->references('id')->on('pelatihans')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
