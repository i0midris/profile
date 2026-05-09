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
        Schema::create('job_openings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('department')->nullable();
            $table->string('department_en')->nullable();
            $table->string('department_ar')->nullable();
            $table->string('location')->nullable();
            $table->string('location_en')->nullable();
            $table->string('location_ar')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('employment_type_en')->nullable();
            $table->string('employment_type_ar')->nullable();
            $table->string('experience_level')->nullable();
            $table->string('experience_level_en')->nullable();
            $table->string('experience_level_ar')->nullable();
            $table->text('description');
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->json('requirements')->nullable();
            $table->json('requirements_en')->nullable();
            $table->json('requirements_ar')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_openings');
    }
};
