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
        // Schema::create('log_penilaians', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('penilaian_id');
        //     $table->string('user_id');
        //     $table->string('pelatihan_id');
        //     $table->timestamp('tanggal_penilaian')->useCurrent();
        //     $table->decimal('skor', 5, 2);
        //     $table->string('keterangan')->nullable();
        //     $table->text('detail')->nullable();
        //     $table->string('created_by')->nullable();
        //     $table->timestamps();

        //     $table->foreign('penilaian_id')->references('id')->on('penilaians')->onDelete('cascade');
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        //     $table->foreign('pelatihan_id')->references('id')->on('pelatihans')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_penilaians');
    }
};
