<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('likes')->insert([
            [
                'blog_id' => 1,
                'user_id' => 2,
                'created_at' => now(),
            ],
            [
                'blog_id' => 1,
                'user_id' => 3,
                'created_at' => now(),
            ],
            [
                'blog_id' => 2,
                'user_id' => 1,
                'created_at' => now(),
            ],
            [
                'blog_id' => 3,
                'user_id' => 4,
                'created_at' => now(),
            ],
            [
                'blog_id' => 4,
                'user_id' => 5,
                'created_at' => now(),
            ],
        ]);
    }
}
