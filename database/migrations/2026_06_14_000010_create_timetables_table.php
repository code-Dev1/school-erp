<?php

use App\Enums\Academic\DayOfWeek;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->restrictOnDelete();
            $table->foreignId('section_id')->constrained('sections')->restrictOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->restrictOnDelete();
            $table->foreignId('teacher_id')->constrained('employees')->restrictOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->restrictOnDelete();
            $table->enum('day_of_week', array_column(DayOfWeek::cases(), 'value'))->index();
            $table->time('start_time')->index();
            $table->time('end_time');
            $table->string('room')->nullable();
            $table->timestamps();

            $table->unique(['class_id', 'section_id', 'academic_year_id', 'day_of_week', 'start_time'], 'class_section_timetable_unique');
            $table->index(['teacher_id', 'day_of_week', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
