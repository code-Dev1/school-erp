<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('father_name')->nullable();
            $table->string('occupation')->nullable();
            $table->string('contact_number')->nullable()->index();
            $table->string('phone')->nullable()->index();
            $table->string('whatsapp_number')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('job')->nullable();
            $table->string('province')->nullable()->index();
            $table->string('district')->nullable()->index();
            $table->string('village')->nullable()->index();
            $table->text('address')->nullable();
            $table->string('status')->default('active')->index();
            $table->text('note')->nullable();
            $table->string('tazkira_number')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};
