<?php

namespace Database\Seeders;

use Exception;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $user = DB::table('users')->where('email', 'johndoe@example.com')->first();
        $category = DB::table('categories')->where('name', 'Computer Science')->first();

        if (!$user || !$category) {
            throw new Exception("User or Category not found. Make sure seeders run in correct order.");
        }

        $title = $faker->sentence();

        DB::table('blogs')->insert([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $faker->paragraphs(4, true),
            'description' => $faker->text(150),
            'thumbnail' => $faker->imageUrl(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
