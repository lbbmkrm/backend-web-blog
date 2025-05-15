<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'created_at' => now(),
            ],
            [
                'username' => 'demo',
                'email' => 'demo@example.com',
                'password' => bcrypt('password123'),
                'is_admin' => false,
                'created_at' => now(),
            ],
            [
                'username' => fake()->userName(),
                'email' => fake()->email(),
                'password' => bcrypt('password123'),
                'is_admin' => false,
                'created_at' => now(),
            ],
            [
                'username' => fake()->userName(),
                'email' => fake()->email(),
                'password' => bcrypt('password123'),
                'is_admin' => false,
                'created_at' => now(),
            ],
            [
                'username' => fake()->userName(),
                'email' => fake()->email(),
                'password' => bcrypt('password123'),
                'is_admin' => false,
                'created_at' => now(),
            ],
            [
                'username' => fake()->userName(),
                'email' => fake()->email(),
                'password' => bcrypt('password123'),
                'is_admin' => false,
                'created_at' => now(),
            ],
            [
                'username' => fake()->userName(),
                'email' => fake()->email(),
                'password' => bcrypt('password123'),
                'is_admin' => false,
                'created_at' => now(),
            ],
        ]);
    }
}
