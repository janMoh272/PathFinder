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
        Schema::create('paths', function (Blueprint $table) {
                $table->id();
                $table->enum('name', ['impulsive', 'reflective'])->unique();
                $table->text('description')->nullable();
                $table->integer('lock_time')->default(0)->comment('بالثواني للمندفع');
                $table->tinyInteger('min_accuracy')->default(70);
                $table->enum('feedback_type', ['immediate', 'delayed', 'corrective', 'reinforcement'])->default('immediate');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paths');
    }
};
