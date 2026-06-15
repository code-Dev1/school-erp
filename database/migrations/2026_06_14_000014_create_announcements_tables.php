<?php

use App\Enums\Communications\AnnouncementType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->enum('type', array_column(AnnouncementType::cases(), 'value'))->default(AnnouncementType::General->value)->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('announcement_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained('announcements')->cascadeOnDelete();
            $table->unsignedBigInteger('recipient_id');
            $table->string('recipient_type');
            $table->timestamp('read_at')->nullable()->index();
            $table->timestamps();

            $table->unique(['announcement_id', 'recipient_id', 'recipient_type'], 'announcement_recipient_unique');
            $table->index(['recipient_id', 'recipient_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_recipients');
        Schema::dropIfExists('announcements');
    }
};
