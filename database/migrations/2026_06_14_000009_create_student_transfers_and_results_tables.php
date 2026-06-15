<?php

use App\Enums\Students\StudentTransferStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('from_class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('from_section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('to_class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('to_section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->string('from_school')->nullable();
            $table->string('to_school')->nullable();
            $table->date('transfer_date')->index();
            $table->text('reason')->nullable();
            $table->enum('status', array_column(StudentTransferStatus::cases(), 'value'))->default(StudentTransferStatus::Pending->value)->index();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['student_id', 'transfer_date']);
        });

        Schema::create('student_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->restrictOnDelete();
            $table->foreignId('section_id')->constrained('sections')->restrictOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->restrictOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->restrictOnDelete();
            $table->string('term')->index();
            $table->string('semester')->nullable();
            $table->string('exam_name')->index();
            $table->string('exam_type')->nullable();
            $table->decimal('marks_obtained', 6, 2);
            $table->decimal('total_marks', 6, 2)->default(100);
            $table->string('grade', 10)->nullable();
            $table->text('remarks')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('result_date')->index();
            $table->date('exam_date')->nullable()->index();
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'academic_year_id', 'term', 'exam_name'], 'student_result_unique');
            $table->index(['class_id', 'section_id', 'academic_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_results');
        Schema::dropIfExists('student_transfers');
    }
};
