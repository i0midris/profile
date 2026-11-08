<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Plan step 3.5: frequently asked questions (with FAQPage structured data).
     */
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question_ar', 500);
            $table->string('question_en', 500)->nullable();
            $table->text('answer_ar');
            $table->text('answer_en')->nullable();
            $table->boolean('show_on_home')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
