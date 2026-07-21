<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('paths')->insert([
            [
                'name' => 'impulsive',
                'description' => 'طالب يضحي بالدقة من أجل السرعة، يحتاج إلى كبح وتدقيق',
                'lock_time' => 5,
                'min_accuracy' => 90,
                'feedback_type' => 'immediate',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'reflective',
                'description' => 'طالب يضحي بالسرعة من أجل الدقة، يحتاج إلى تحفيز وتبسيط خيارات',
                'lock_time' => 0,
                'min_accuracy' => 70,
                'feedback_type' => 'delayed',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    
    }
}
