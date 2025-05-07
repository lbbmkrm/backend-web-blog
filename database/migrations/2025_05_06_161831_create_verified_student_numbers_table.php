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
        Schema::create('verified_student_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('student_id_number', 20)->unique();
            $table->string('name', 100);
            $table->string('university', 100);
            $table->string('faculty', 100);
            $table->string('study_program', 100);
            $table->string('batch', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verified_student_numbers');
    }
};
