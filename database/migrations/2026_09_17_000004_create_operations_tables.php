<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calculator_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('certificate_track_id')->nullable()->constrained()->nullOnDelete();
            $table->string('country')->nullable()->index();
            $table->string('rule_type')->default('equivalency')->index();
            $table->jsonb('formula')->nullable();
            $table->jsonb('inputs_schema')->nullable();
            $table->jsonb('result_schema')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('equivalency_centers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country')->index();
            $table->string('city')->nullable()->index();
            $table->string('authority')->nullable();
            $table->text('address')->nullable();
            $table->text('website_url')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->jsonb('required_documents')->nullable();
            $table->text('processing_notes')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equivalency_centers');
        Schema::dropIfExists('calculator_rules');
    }
};
