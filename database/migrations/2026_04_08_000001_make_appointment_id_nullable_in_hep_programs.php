<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Make appointment_id nullable in hep_programs table to allow
     * HEP programs to be generated without requiring an appointment.
     */
    public function up(): void
    {
        Schema::table('hep_programs', function (Blueprint $table) {
            $table->foreignId('appointment_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hep_programs', function (Blueprint $table) {
            $table->foreignId('appointment_id')->nullable(false)->change();
        });
    }
};
