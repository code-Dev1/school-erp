<?php

use App\Enums\Employees\ContractType;
use App\Enums\Employees\EmployeeStatus;
use App\Enums\Employees\EmployeeType;
use App\Enums\Students\StudentGender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('name')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('father_name')->nullable();
            $table->string('grandfather_name')->nullable();
            $table->string('tazkira_number')->nullable()->unique();
            $table->date('date_of_birth')->nullable()->index();
            $table->enum('gender', array_column(StudentGender::cases(), 'value'))->nullable()->index();
            $table->string('photo_path')->nullable();
            $table->string('province')->nullable()->index();
            $table->string('district')->nullable()->index();
            $table->string('village')->nullable()->index();
            $table->text('address')->nullable();
            $table->string('contact_number')->nullable()->index();
            $table->string('phone')->nullable()->index();
            $table->string('whatsapp_number')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('blood_group', 10)->nullable();
            $table->enum('type', array_column(EmployeeType::cases(), 'value'))->index();
            $table->string('teacher_type')->nullable()->index();
            $table->string('custom_type')->nullable();
            $table->string('job_title')->index();
            $table->string('custom_job_title')->nullable();
            $table->string('department')->nullable()->index();
            $table->string('education_level')->nullable();
            $table->string('field_of_study')->nullable();
            $table->date('hired_at')->index();
            $table->enum('contract_type', array_column(ContractType::cases(), 'value'))->index();
            $table->decimal('base_salary', 12, 2)->default(0);
            $table->decimal('salary', 12, 2)->nullable();
            $table->string('bank_account')->nullable();
            $table->foreignId('reports_to')->nullable()->constrained('employees')->nullOnDelete();
            $table->enum('status', array_column(EmployeeStatus::cases(), 'value'))->default(EmployeeStatus::Active->value)->index();
            $table->unsignedBigInteger('biometric_uid')->nullable()->unique();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'status']);
            $table->index(['employee_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
