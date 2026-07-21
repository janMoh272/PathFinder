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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->enum('type', ['mcq', 'truefalse', 'matching', 'production'])->default('mcq');
              $table->enum('content_type',['text','image'])->default('text');
            $table->tinyInteger('difficulty')->default(1)->comment('1=سهل، 5=صعب');
            $table->text('correct_answer');
            $table->json('options')->nullable()->comment('للأسئلة متعددة الخيارات');
            $table->foreignId('path_id')->nullable()->constrained()->onDelete('set null')->comment('NULL=للتقييم');
            $table->integer('time_limit')->nullable()->default(60)->comment('الحد الأقصى للثواني');
            $table->text('explanation')->nullable()->comment('شرح الإجابة للتغذية الراجعة');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
