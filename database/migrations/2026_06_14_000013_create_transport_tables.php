<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_services', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_plate_number')->unique();
            $table->unsignedSmallInteger('vehicle_capacity');
            $table->string('vehicle_type')->nullable();
            $table->string('driver_name');
            $table->string('driver_phone')->nullable()->index();
            $table->string('driver_license_number')->nullable()->unique();
            $table->decimal('driver_monthly_salary', 12, 2)->default(0);
            $table->string('route_name');
            $table->string('pickup_area')->nullable()->index();
            $table->string('dropoff_area')->nullable()->index();
            $table->decimal('monthly_fee', 12, 2)->default(0);
            $table->string('status')->default('active')->index();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('student_transport', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('transport_service_id')->constrained('transport_services')->restrictOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->decimal('fee_amount', 12, 2)->default(0);
            $table->date('starts_at')->nullable()->index();
            $table->date('ends_at')->nullable()->index();
            $table->string('status')->default('active')->index();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'transport_service_id'], 'student_transport_unique');
            $table->index(['transport_service_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_transport');
        Schema::dropIfExists('transport_services');
    }
};
