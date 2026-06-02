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
        Schema::create('fuzzy_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_reading_id')->constrained('sensor_readings')->cascadeOnDelete();

            $table->string('himpunan_ph');
            $table->string('himpunan_suhu');
            $table->float('nilai_defuzz_pompa_ph');
            $table->float('nilai_defuzz_misting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuzzy_logs');
    }
};
