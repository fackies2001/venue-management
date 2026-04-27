<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();

            // Event Details
            $table->string('event_title');
            $table->string('agenda')->nullable();
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('expected_attendees');
            $table->text('purpose')->nullable();
            $table->string('building');

            // Booker Info
            $table->string('booker_name');
            $table->string('service');
            $table->string('division');
            $table->string('email');
            $table->string('phone');

            // Attachment
            $table->string('attachment_path')->nullable();

            // Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'completed', 'moved'])
                ->default('pending');
            $table->text('admin_remarks')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['venue_id', 'event_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
