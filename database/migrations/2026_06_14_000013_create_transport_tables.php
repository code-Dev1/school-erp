<?php

use App\Enums\Transport\VehicleStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique();
            $table->string('driver_name');
            $table->string('driver_contact')->nullable()->index();
            $table->unsignedSmallInteger('capacity');
            $table->enum('status', array_column(VehicleStatus::cases(), 'value'))->default(VehicleStatus::Active->value)->index();
            $table->timestamps();
        });

        Schema::create('transport_routes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('student_transport', function (Blueprint $table) {
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->restrictOnDelete();
            $table->foreignId('route_id')->constrained('transport_routes')->restrictOnDelete();
            $table->decimal('fee_amount', 12, 2)->default(0);
            $table->date('starts_at')->nullable()->index();
            $table->date('ends_at')->nullable()->index();
            $table->timestamps();

            $table->primary(['student_id', 'vehicle_id', 'route_id']);
            $table->index(['vehicle_id', 'route_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_transport');
        Schema::dropIfExists('transport_routes');
        Schema::dropIfExists('vehicles');
    }
};
