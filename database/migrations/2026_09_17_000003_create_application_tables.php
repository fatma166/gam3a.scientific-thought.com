<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('certificate_track_id')->nullable()->constrained()->nullOnDelete();
            $table->string('academic_year');
            $table->string('status')->default('draft')->index();
            $table->string('full_name');
            $table->string('passport_number')->nullable()->index();
            $table->string('nationality')->nullable()->index();
            $table->decimal('score', 5, 2)->nullable();
            $table->jsonb('meta')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('application_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_id')->constrained()->restrictOnDelete();
            $table->unsignedTinyInteger('rank');
            $table->string('status')->default('selected')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['application_id', 'rank']);
            $table->unique(['application_id', 'program_id']);
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('type')->index();
            $table->string('original_name');
            $table->string('disk')->default('s3');
            $table->text('path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('status')->default('pending_review')->index();
            $table->text('review_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->index();
            $table->string('provider_reference')->nullable()->index();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('EGP');
            $table->string('status')->default('pending')->index();
            $table->timestamp('paid_at')->nullable();
            $table->jsonb('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('application_choices');
        Schema::dropIfExists('applications');
    }
};
