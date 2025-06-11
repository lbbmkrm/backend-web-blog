<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['user_id' => 1, 'name' => 'Mahasiswa'],
            ['user_id' => 1, 'name' => 'Kampus'],
            ['user_id' => 1, 'name' => 'Belajar'],
            ['user_id' => 1, 'name' => 'Teknologi'],
            ['user_id' => 1, 'name' => 'Bisnis'],
            ['user_id' => 1, 'name' => 'Kesehatan Mental'],
            ['user_id' => 1, 'name' => 'Pemasaran'],
            ['user_id' => 1, 'name' => 'Film'],
            ['user_id' => 1, 'name' => 'Digital'],
            ['user_id' => 1, 'name' => 'Meditasi'],
        ];

        foreach ($tags as $tag) {
            DB::table('tags')->insert([
                'user_id' => $tag['user_id'],
                'name' => $tag['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
