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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // النوع هنا للاختبار هل الطالب في حاله تحديد النمط ام في مرحله التعليم ام الاختبار الاخير 
            $table->enum('type', ['classification', 'learning', 'final'])->default('classification');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            // حاله   abandoned تشير ان الطالب بدلء في الاختبار لكن لم ينتهي وتركة ل ايام عديده  
            $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress');
            $table->integer('total_score')->nullable();
            $table->integer('total_errors')->default(0);
            $table->integer('total_time')->default(0);
            $table->integer('current_question_index')->default(0)->comment('أين وصل الطالب');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
