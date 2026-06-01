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
        Schema::table('device_statuses', function (Blueprint $table) {
            $table->enum('growlight', ['ON', 'OFF'])->default('OFF')->after('pompa_ph_down');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_statuses', function (Blueprint $table) {
            // Menghapus kolom jika sewaktu-waktu migration di-rollback
            $table->dropColumn('growlight');
        });
    }
};
