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
        Schema::create('device_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->unique();
            $table->string('mode_sistem')->default('AUTO');
            $table->string('pompa_ph_up')->default('OFF');
            $table->string('pompa_ph_down')->default('OFF');
            $table->string('misting')->default('OFF');
            $table->string('pompa_nutrisi')->default('OFF');
            $table->timestamp('override_until')->nullable(); //ini untuk batas waktu kembali ke mode auto(manual)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_statuses');
    }
};
