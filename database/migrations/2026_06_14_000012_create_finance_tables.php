<?php

use App\Enums\Finance\FeePaymentStatus;
use App\Enums\Finance\SalaryComponentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->restrictOnDelete();
            $table->foreignId('fee_type_id')->constrained('fee_types')->restrictOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->restrictOnDelete();
            $table->decimal('amount', 12, 2);
            $table->unsignedTinyInteger('due_day')->default(1);
            $table->timestamps();

            $table->unique(['class_id', 'fee_type_id', 'academic_year_id'], 'fee_structure_unique');
        });

        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->restrictOnDelete();
            $table->foreignId('fee_structure_id')->constrained('fee_structures')->restrictOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('remaining_amount', 12, 2)->default(0);
            $table->date('due_date')->nullable()->index();
            $table->date('payment_date')->index();
            $table->date('covers_from')->nullable()->index();
            $table->date('covers_to')->nullable()->index();
            $table->unsignedTinyInteger('months_count')->default(1);
            $table->enum('status', array_column(FeePaymentStatus::cases(), 'value'))->default(FeePaymentStatus::Pending->value)->index();
            $table->string('receipt_number')->unique();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index(['fee_structure_id', 'status']);
        });

        Schema::create('fee_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('fee_structure_id')->nullable()->constrained('fee_structures')->nullOnDelete();
            $table->foreignId('fee_payment_id')->nullable()->constrained('fee_payments')->nullOnDelete();
            $table->decimal('due_amount', 12, 2)->default(0);
            $table->date('due_date')->index();
            $table->string('status')->default('open')->index();
            $table->timestamp('sent_at')->nullable()->index();
            $table->text('message')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status', 'due_date']);
        });

        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('type', array_column(SalaryComponentType::cases(), 'value'))->index();
            $table->string('title');
            $table->decimal('amount', 12, 2);
            $table->timestamps();

            $table->index(['employee_id', 'type']);
        });

        Schema::create('payroll_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->restrictOnDelete();
            $table->unsignedTinyInteger('month')->index();
            $table->unsignedSmallInteger('year')->index();
            $table->decimal('base_salary', 12, 2);
            $table->decimal('total_allowances', 12, 2)->default(0);
            $table->decimal('total_deductions', 12, 2)->default(0);
            $table->decimal('absence_deduction', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2);
            $table->timestamp('paid_at')->nullable()->index();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['employee_id', 'month', 'year'], 'payroll_employee_month_year_unique');
            $table->index(['year', 'month']);
        });

        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->morphs('payable');
            $table->unsignedTinyInteger('month')->index();
            $table->unsignedSmallInteger('year')->index();
            $table->decimal('base_salary', 12, 2);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('deduction', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2);
            $table->timestamp('paid_at')->nullable()->index();
            $table->string('status')->default('pending')->index();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['payable_type', 'payable_id', 'month', 'year'], 'salary_payable_month_year_unique');
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->index();
            $table->decimal('amount', 12, 2);
            $table->date('date')->index();
            $table->date('expense_date')->nullable()->index();
            $table->string('paid_by')->nullable();
            $table->text('description')->nullable();
            $table->string('receipt_image')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('salaries');
        Schema::dropIfExists('payroll_records');
        Schema::dropIfExists('salary_components');
        Schema::dropIfExists('fee_alerts');
        Schema::dropIfExists('fee_payments');
        Schema::dropIfExists('fee_structures');
        Schema::dropIfExists('fee_types');
    }
};
