<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VerifiedStudentNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $prefix = '2214370';

        $university = 'Universitas Pembangunan Panca Budi';

        // Program studi dikelompokkan sesuai fakultas
        $studyPrograms = [
            'Fakultas Sosial Sains' => [
                'Akuntansi',
                'Ekonomi Pembangunan',
                'Ilmu Hukum',
                'Manajemen',
            ],
            'Fakultas Sains dan Teknologi' => [
                'Agroteknologi',
                'Agribisnis',
                'Peternakan',
                'Sistem Komputer',
                'Teknik Elektro',
                'Arsitektur',
                'Teknologi Informasi',
                'Teknik Sipil',
            ],
        ];

        $batches = ['2019', '2020', '2021', '2022', '2023'];

        for ($i = 1; $i <= 200; $i++) {
            $faculty = array_rand($studyPrograms);
            $program = $studyPrograms[$faculty][array_rand($studyPrograms[$faculty])];

            DB::table('verified_student_numbers')->insert([
                'student_id_number' => $prefix . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => fake()->firstName() . ' ' . fake()->lastName(),
                'university' => $university,
                'faculty' => $faculty,
                'study_program' => $program,
                'batch' => $batches[array_rand($batches)],
            ]);
        }
    }
}
