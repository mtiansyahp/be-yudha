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
        Schema::create('pelatihan_user', function (Blueprint $table) {
            $table->id();

            // Sesuaikan dengan VARCHAR(255) dari pelatihans.id dan users.id
            $table->string('pelatihan_id');
            $table->string('user_id');

            // Foreign key sesuai tipe string
            $table->foreign('pelatihan_id')->references('id')->on('pelatihans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelatihan_user');
    }
};
