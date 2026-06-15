<?php

use App\Enums\Students\StudentGender;
use App\Enums\Students\StudentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('asas_number')->nullable()->unique();
            $table->string('name')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('father_name');
            $table->string('grandfather_name');
            $table->string('tazkira_number')->unique();
            $table->date('date_of_birth')->nullable()->index();
            $table->enum('gender', array_column(StudentGender::cases(), 'value'))->index();
            $table->string('photo_path')->nullable();
            $table->string('province')->nullable()->index();
            $table->string('district')->nullable()->index();
            $table->string('village')->nullable()->index();
            $table->string('contact_number')->nullable()->index();
            $table->text('address')->nullable();
            $table->string('student_type')->default('new')->index();
            $table->string('blood_group', 10)->nullable();
            $table->foreignId('class_id')->constrained('classes')->restrictOnDelete();
            $table->foreignId('section_id')->constrained('sections')->restrictOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->restrictOnDelete();
            $table->date('admission_date')->index();
            $table->enum('status', array_column(StudentStatus::cases(), 'value'))->default(StudentStatus::Active->value)->index();
            $table->string('previous_school')->nullable();
            $table->string('transfer_document')->nullable();
            $table->unsignedBigInteger('biometric_uid')->nullable()->unique();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['class_id', 'section_id', 'status']);
            $table->index(['academic_year_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
