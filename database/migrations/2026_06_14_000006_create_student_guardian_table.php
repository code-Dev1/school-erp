<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_guardian', function (Blueprint $table) {
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('guardian_id')->constrained('guardians')->cascadeOnDelete();
            $table->string('relationship')->default('guardian')->index();
            $table->boolean('is_primary')->default(false)->index();
            $table->timestamps();

            $table->primary(['student_id', 'guardian_id']);
        });

        Schema::create('class_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->string('academic_year')->nullable()->index();
            $table->string('status')->default('active')->index();
            $table->timestamps();

            $table->unique(['student_id', 'class_id', 'academic_year'], 'class_student_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_student');
        Schema::dropIfExists('student_guardian');
    }
};
