<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Create patient_analyses table for legacy patient analysis records.
     * This separates PatientAnalysis from PatientData which were incorrectly sharing the same table.
     * 
     * Includes data migration to copy existing records from patient_data to patient_analyses.
     */
    public function up(): void
    {
        if (!Schema::hasTable('patient_analyses')) {
            Schema::create('patient_analyses', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('age')->nullable();
                $table->string('gender')->nullable();
                $table->string('weight')->nullable();
                $table->string('height')->nullable();
                $table->string('temperature')->nullable();
                $table->string('blood_pressure')->nullable();
                $table->string('blood_sugar')->nullable();
                $table->json('symptoms')->nullable();
                $table->text('test_results')->nullable();
                $table->text('preliminary_diagnosis')->nullable();
                $table->longText('ai_response')->nullable();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('assigned_patient_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('previous_record_id')->nullable()->constrained('patient_analyses')->nullOnDelete();
                $table->integer('visit_number')->default(1);
                $table->string('patient_key')->nullable();

                // Enhanced medical fields
                $table->text('chief_complaint')->nullable();
                $table->string('symptom_duration')->nullable();
                $table->text('past_medical_history')->nullable();
                $table->text('medication_history')->nullable();
                $table->text('allergies')->nullable();
                $table->text('past_medications')->nullable();
                $table->text('family_history')->nullable();
                $table->text('social_history')->nullable();
                $table->string('pain_scale')->nullable();
                $table->string('visit_type')->nullable();
                $table->string('heart_rate')->nullable();
                $table->string('respiratory_rate')->nullable();
                $table->string('oxygen_saturation')->nullable();
                $table->text('physician_notes')->nullable();
                $table->text('additional_notes')->nullable();

                // Head-to-Toe Assessment fields
                $table->string('consciousness_level')->nullable();
                $table->string('mood_behavior')->nullable();
                $table->string('speech_clarity')->nullable();
                $table->string('hygiene_level')->nullable();
                $table->string('scalp_condition')->nullable();
                $table->string('pupil_reactivity')->nullable();
                $table->string('vision_issues')->nullable();
                $table->string('hearing_issues')->nullable();
                $table->string('oral_findings')->nullable();
                $table->string('orientation_level')->nullable();
                $table->string('limb_strength')->nullable();
                $table->string('reflexes')->nullable();
                $table->string('sensation_findings')->nullable();
                $table->string('trachea_position')->nullable();
                $table->string('jvd_present')->nullable();
                $table->string('lung_sounds')->nullable();
                $table->string('heart_sounds')->nullable();
                $table->string('capillary_refill_time')->nullable();
                $table->string('abdominal_shape')->nullable();
                $table->string('bowel_sounds')->nullable();
                $table->string('abdominal_tenderness')->nullable();
                $table->string('nausea_or_vomiting')->nullable();
                $table->string('appetite_level')->nullable();
                $table->string('urination_issues')->nullable();
                $table->string('catheter_present')->nullable();
                $table->string('urine_characteristics')->nullable();
                $table->string('range_of_motion')->nullable();
                $table->string('gait_stability')->nullable();
                $table->string('assistive_devices')->nullable();
                $table->string('skin_color')->nullable();
                $table->string('skin_temperature')->nullable();
                $table->string('skin_lesions')->nullable();
                $table->string('pressure_ulcers')->nullable();
                $table->string('pain_description')->nullable();

                $table->timestamps();

                $table->index(['user_id', 'patient_key']);
                $table->index(['user_id', 'created_at']);
            });
        }

        // DATA MIGRATION: Copy existing records from patient_data to patient_analyses
        // This ensures no data loss when PatientAnalysis model switches to its own table
        if (Schema::hasTable('patient_data')) {
            $existingRecords = DB::table('patient_data')->get();
            
            if ($existingRecords->isNotEmpty()) {
                $now = now();
                
                foreach ($existingRecords as $record) {
                    DB::table('patient_analyses')->insert([
                        'name' => $record->name ?? 'Unknown',
                        'age' => isset($record->age) ? (string)$record->age : null,
                        'gender' => $record->gender ?? null,
                        'weight' => isset($record->weight) ? (string)$record->weight : null,
                        'height' => isset($record->height) ? (string)$record->height : null,
                        'temperature' => isset($record->temperature) ? (string)$record->temperature : null,
                        'blood_pressure' => $record->blood_pressure ?? null,
                        'blood_sugar' => isset($record->blood_sugar) ? (string)$record->blood_sugar : null,
                        'symptoms' => $record->symptoms ?? null,
                        'test_results' => $record->test_results ?? null,
                        'preliminary_diagnosis' => $record->preliminary_diagnosis ?? null,
                        'ai_response' => $record->ai_response ?? null,
                        'user_id' => $record->user_id,
                        'assigned_patient_id' => $record->assigned_patient_id ?? null,
                        'previous_record_id' => $record->previous_record_id ?? null,
                        'visit_number' => $record->visit_number ?? 1,
                        'patient_key' => $record->patient_key ?? null,
                        
                        // Enhanced medical fields (if they exist in source)
                        'chief_complaint' => $record->chief_complaint ?? null,
                        'symptom_duration' => $record->symptom_duration ?? null,
                        'past_medical_history' => $record->past_medical_history ?? null,
                        'medication_history' => $record->medication_history ?? null,
                        'allergies' => $record->allergies ?? null,
                        'past_medications' => $record->past_medications ?? null,
                        'family_history' => $record->family_history ?? null,
                        'social_history' => $record->social_history ?? null,
                        'pain_scale' => $record->pain_scale ?? null,
                        'visit_type' => $record->visit_type ?? null,
                        'heart_rate' => $record->heart_rate ?? null,
                        'respiratory_rate' => $record->respiratory_rate ?? null,
                        'oxygen_saturation' => $record->oxygen_saturation ?? null,
                        'physician_notes' => $record->physician_notes ?? null,
                        'additional_notes' => $record->additional_notes ?? null,
                        
                        // Head-to-Toe Assessment fields
                        'consciousness_level' => $record->consciousness_level ?? null,
                        'mood_behavior' => $record->mood_behavior ?? null,
                        'speech_clarity' => $record->speech_clarity ?? null,
                        'hygiene_level' => $record->hygiene_level ?? null,
                        'scalp_condition' => $record->scalp_condition ?? null,
                        'pupil_reactivity' => $record->pupil_reactivity ?? null,
                        'vision_issues' => $record->vision_issues ?? null,
                        'hearing_issues' => $record->hearing_issues ?? null,
                        'oral_findings' => $record->oral_findings ?? null,
                        'orientation_level' => $record->orientation_level ?? null,
                        'limb_strength' => $record->limb_strength ?? null,
                        'reflexes' => $record->reflexes ?? null,
                        'sensation_findings' => $record->sensation_findings ?? null,
                        'trachea_position' => $record->trachea_position ?? null,
                        'jvd_present' => $record->jvd_present ?? null,
                        'lung_sounds' => $record->lung_sounds ?? null,
                        'heart_sounds' => $record->heart_sounds ?? null,
                        'capillary_refill_time' => $record->capillary_refill_time ?? null,
                        'abdominal_shape' => $record->abdominal_shape ?? null,
                        'bowel_sounds' => $record->bowel_sounds ?? null,
                        'abdominal_tenderness' => $record->abdominal_tenderness ?? null,
                        'nausea_or_vomiting' => $record->nausea_or_vomiting ?? null,
                        'appetite_level' => $record->appetite_level ?? null,
                        'urination_issues' => $record->urination_issues ?? null,
                        'catheter_present' => $record->catheter_present ?? null,
                        'urine_characteristics' => $record->urine_characteristics ?? null,
                        'range_of_motion' => $record->range_of_motion ?? null,
                        'gait_stability' => $record->gait_stability ?? null,
                        'assistive_devices' => $record->assistive_devices ?? null,
                        'skin_color' => $record->skin_color ?? null,
                        'skin_temperature' => $record->skin_temperature ?? null,
                        'skin_lesions' => $record->skin_lesions ?? null,
                        'pressure_ulcers' => $record->pressure_ulcers ?? null,
                        'pain_description' => $record->pain_description ?? null,
                        
                        'created_at' => $record->created_at ?? $now,
                        'updated_at' => $record->updated_at ?? $now,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_analyses');
    }
};
