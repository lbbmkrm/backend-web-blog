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
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@example.com',
                'password' => bcrypt('password123'),
                'img' => fake()->imageUrl(),
                'bio' => 'Mahasiswa Teknik Informatika angkatan 2022.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti.aminah@example.com',
                'password' => bcrypt('password123'),
                'img' => fake()->imageUrl(),
                'bio' => 'Pecinta AI dan Data Science.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'password' => bcrypt('password123'),
                'img' => null,
                'bio' => 'Mahasiswa aktif dalam kegiatan kampus.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@example.com',
                'password' => bcrypt('password123'),
                'img' => fake()->imageUrl(),
                'bio' => 'Sedang menulis skripsi tentang machine learning.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rizky Hidayat',
                'email' => 'rizky.hidayat@example.com',
                'password' => bcrypt('password123'),
                'img' => null,
                'bio' => 'Mahasiswa semester 6, hobi ngoding Laravel.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
