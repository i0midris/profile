<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Product pages (POS, ERP, HR, stores, sales-rep apps...) — plan step 3.1.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('tagline_ar')->nullable();
            $table->string('tagline_en')->nullable();
            $table->text('description_ar');
            $table->text('description_en')->nullable();
            $table->json('features_ar')->nullable();
            $table->json('features_en')->nullable();
            $table->json('sectors_ar')->nullable();
            $table->json('sectors_en')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('screenshots')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
