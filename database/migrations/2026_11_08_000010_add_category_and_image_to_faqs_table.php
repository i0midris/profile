<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The FAQ moves to its own page: questions get a category (tabs) and an optional
     * illustration shown next to the answer.
     */
    public function up(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->string('category', 30)->default('general')->after('id');
            $table->string('image')->nullable()->after('answer_en');
        });
    }

    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropColumn(['category', 'image']);
        });
    }
};
