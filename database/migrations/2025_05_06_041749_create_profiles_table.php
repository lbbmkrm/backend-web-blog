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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name', 100)->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->unsignedBigInteger('student_number')->unique();
            $table->string('university', 100)->nullable();
            $table->string('faculty', 100)->nullable();
            $table->string('study_program', 100)->nullable();
            $table->string('batch', 10)->nullable();
            $table->string('phone', 20)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
