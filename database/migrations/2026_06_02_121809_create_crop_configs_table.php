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
        Schema::create('crop_configs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tanaman');
            $table->float('batas_bawah_ph');
            $table->float('batas_atas_ph');
            $table->float('batas_bawah_suhu');
            $table->float('batas_atas_suhu');
            $table->float('batas_bawah_kelembapan');
            $table->float('batas_atas_kelembapan');
            $table->integer('batas_bawah_cahaya');
            $table->integer('batas_atas_cahaya');
            $table->integer('batas_bawah_tds');
            $table->integer('batas_atas_tds');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_configs');
    }
};
