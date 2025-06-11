<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlogTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('blog_tags')->insert([
            ['blog_id' => 1, 'tag_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['blog_id' => 1, 'tag_id' => 9, 'created_at' => now(), 'updated_at' => now()],

            ['blog_id' => 2, 'tag_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['blog_id' => 2, 'tag_id' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['blog_id' => 3, 'tag_id' => 6, 'created_at' => now(), 'updated_at' => now()],

            ['blog_id' => 4, 'tag_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['blog_id' => 7, 'tag_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['blog_id' => 7, 'tag_id' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['blog_id' => 8, 'tag_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['blog_id' => 9, 'tag_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['blog_id' => 10, 'tag_id' => 8, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
