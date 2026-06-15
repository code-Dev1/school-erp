<?php

use App\Enums\Biometric\AttendanceStatus;
use App\Enums\Biometric\BiometricDeviceStatus;
use App\Enums\Biometric\BiometricDeviceType;
use App\Enums\Biometric\BiometricLogType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biometric_devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip_address', 45)->unique();
            $table->unsignedSmallInteger('port')->default(4370);
            $table->string('location')->nullable()->index();
            $table->enum('device_type', array_column(BiometricDeviceType::cases(), 'value'))->default(BiometricDeviceType::Zkteco->value)->index();
            $table->enum('status', array_column(BiometricDeviceStatus::cases(), 'value'))->default(BiometricDeviceStatus::Active->value)->index();
            $table->timestamps();
        });

        Schema::create('biometric_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('biometric_uid')->index();
            $table->nullableMorphs('person');
            $table->foreignId('device_id')->constrained('biometric_devices')->restrictOnDelete();
            $table->timestamp('timestamp')->index();
            $table->timestamp('check_time')->nullable()->index();
            $table->enum('log_type', array_column(BiometricLogType::cases(), 'value'))->index();
            $table->string('check_type')->nullable()->index();
            $table->json('raw_data')->nullable();
            $table->timestamp('synced_at')->nullable()->index();
            $table->timestamps();

            $table->unique(['biometric_uid', 'device_id', 'timestamp', 'log_type'], 'biometric_log_unique');
        });

        Schema::create('attendance_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');
            $table->string('person_type');
            $table->date('date')->index();
            $table->enum('status', array_column(AttendanceStatus::cases(), 'value'))->index();
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->timestamps();

            $table->unique(['person_id', 'person_type', 'date'], 'attendance_person_date_unique');
            $table->index(['person_type', 'status', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_summaries');
        Schema::dropIfExists('biometric_logs');
        Schema::dropIfExists('biometric_devices');
    }
};
