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
            $table->string('himpunan_cahaya')->default('NORMAL')->after('himpunan_suhu');
            $table->decimal('nilai_defuzz_growlight', 8, 2)->default(0)->after('nilai_defuzz_misting');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fuzzy_logs', function (Blueprint $table) {
            $table->dropColumn(['himpunan_cahaya', 'nilai_defuzz_growlight']);
        });
    }
};
