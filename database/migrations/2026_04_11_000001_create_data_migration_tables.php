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
        // Create data_migration_logs table
        Schema::create('data_migration_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->constrained('users')->onDelete('cascade');
            $table->string('source_system')->nullable();
            $table->string('file_name');
            $table->string('file_type'); // csv, excel, json, sql
            $table->string('entity_type'); // doctor, patient, patient_data, diagnosis
            $table->integer('total_rows')->default(0);
            $table->integer('imported_count')->default(0);
            $table->integer('skipped_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->json('failure_log')->nullable(); // [{row: 5, reason: "missing email", data: {...}}]
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create data_migration_mappings table
        Schema::create('data_migration_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('source_system')->nullable(); // null = global template
            $table->string('entity_type'); // doctor, patient, patient_data, diagnosis
            $table->string('source_column');
            $table->string('target_field');
            $table->float('confidence')->default(1.0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Add source_identifiers to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'source_identifiers')) {
                $table->json('source_identifiers')->nullable()->after('stripe_customer_id');
            }
        });

        // Add source_record_id to patient_data table
        Schema::table('patient_data', function (Blueprint $table) {
            if (!Schema::hasColumn('patient_data', 'source_record_id')) {
                $table->string('source_record_id')->nullable()->after('id');
            }
        });

        // Add source_record_id to diagnoses table
        Schema::table('diagnoses', function (Blueprint $table) {
            if (!Schema::hasColumn('diagnoses', 'source_record_id')) {
                $table->string('source_record_id')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diagnoses', function (Blueprint $table) {
            if (Schema::hasColumn('diagnoses', 'source_record_id')) {
                $table->dropColumn('source_record_id');
            }
        });

        Schema::table('patient_data', function (Blueprint $table) {
            if (Schema::hasColumn('patient_data', 'source_record_id')) {
                $table->dropColumn('source_record_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'source_identifiers')) {
                $table->dropColumn('source_identifiers');
            }
        });

        Schema::dropIfExists('data_migration_mappings');
        Schema::dropIfExists('data_migration_logs');
    }
};
