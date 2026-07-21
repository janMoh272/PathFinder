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
        Schema::create('test_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->integer('order_num')->default(0);//تحفظ رقم الاسئلة العشوائية مثلا السوال الخامس سيكون هنا 0
            $table->integer('points')->default(1);// تساعد في حفظ النقاط التي حصل عليها الطالب مثلا اذا كان السوال اصعب تكون النقاط اعلى
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_details');
    }
};
