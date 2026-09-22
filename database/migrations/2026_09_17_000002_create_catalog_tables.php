<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('city')->index();
            $table->string('type')->index();
            $table->string('acceptance_label')->nullable();
            $table->text('image_url')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->unique(['university_id', 'slug']);
        });

        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->string('degree')->default('Bachelor');
            $table->string('language')->default('Arabic');
            $table->unsignedTinyInteger('duration_years')->nullable();
            $table->decimal('tuition_amount', 12, 2)->nullable();
            $table->string('tuition_currency', 3)->default('EGP');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->unique(['faculty_id', 'slug']);
        });

        Schema::create('certificate_tracks', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('country')->nullable()->index();
            $table->jsonb('requirements')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('admission_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('certificate_track_id')->constrained()->cascadeOnDelete();
            $table->decimal('minimum_score', 5, 2)->nullable();
            $table->jsonb('required_subjects')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->unique(['program_id', 'certificate_track_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_rules');
        Schema::dropIfExists('certificate_tracks');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('faculties');
        Schema::dropIfExists('universities');
    }
};
