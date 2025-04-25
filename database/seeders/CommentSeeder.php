<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('comments')->insert([
            [
                'blog_id' => 1,
                'user_id' => 2,
                'content' => 'Artikel yang sangat bermanfaat, terima kasih!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'blog_id' => 1,
                'user_id' => 3,
                'content' => 'Saya setuju dengan poin-poin yang disampaikan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'blog_id' => 2,
                'user_id' => 1,
                'content' => 'Tipsnya sangat membantu saya belajar.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'blog_id' => 3,
                'user_id' => 4,
                'content' => 'Mental health awareness itu penting banget!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'blog_id' => 4,
                'user_id' => 5,
                'content' => 'Keren, saya juga lagi bangun bisnis online.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
