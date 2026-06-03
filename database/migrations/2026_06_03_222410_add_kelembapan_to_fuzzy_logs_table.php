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
        Schema::table('fuzzy_logs', function (Blueprint $table) {
            // Menambahkan kolom himpunan_kelembapan setelah himpunan_suhu
            $table->string('himpunan_kelembapan')->nullable()->after('himpunan_suhu');
        });
    }

    public function down(): void
    {
        Schema::table('fuzzy_logs', function (Blueprint $table) {
            $table->dropColumn('himpunan_kelembapan');
        });
    }
};
