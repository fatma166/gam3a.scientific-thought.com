<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('eligibility_search_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('certificate_track_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_id')->nullable()->constrained()->nullOnDelete();
            $table->string('specialty_keyword')->nullable()->index();
            $table->decimal('minimum_score', 5, 2)->default(0)->index();
            $table->unsignedInteger('priority')->default(0)->index();
            $table->text('message')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eligibility_search_rules');
    }
};
