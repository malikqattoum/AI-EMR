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
        if (!Schema::hasTable('analytics_dashboards')) {
            Schema::create('analytics_dashboards', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->json('configuration')->nullable();
                $table->boolean('is_default')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['user_id', 'is_active']);
                $table->index(['user_id', 'is_default']);
            });
        }

        if (!Schema::hasTable('dashboard_widgets')) {
            Schema::create('dashboard_widgets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('dashboard_id')->constrained('analytics_dashboards')->onDelete('cascade');
                $table->string('name');
                $table->string('type'); // chart, metric, table, etc.
                $table->integer('position')->default(0);
                $table->json('configuration')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['dashboard_id', 'is_active']);
            });
        }

        if (!Schema::hasTable('analytics_metrics')) {
            Schema::create('analytics_metrics', function (Blueprint $table) {
                $table->id();
                $table->foreignId('dashboard_id')->constrained('analytics_dashboards')->onDelete('cascade');
                $table->foreignId('dashboard_widget_id')->nullable()->constrained('dashboard_widgets')->onDelete('cascade');
                $table->string('metric_key');
                $table->string('metric_type'); // counter, gauge, histogram, etc.
                $table->json('data')->nullable();
                $table->json('configuration')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['dashboard_id', 'metric_key']);
            });
        }

        if (!Schema::hasTable('dashboard_filters')) {
            Schema::create('dashboard_filters', function (Blueprint $table) {
                $table->id();
                $table->foreignId('dashboard_id')->constrained('analytics_dashboards')->onDelete('cascade');
                $table->string('field');
                $table->string('operator')->default('=');
                $table->string('default_value')->nullable();
                $table->integer('order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['dashboard_id', 'field']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_filters');
        Schema::dropIfExists('analytics_metrics');
        Schema::dropIfExists('dashboard_widgets');
        Schema::dropIfExists('analytics_dashboards');
    }
};
