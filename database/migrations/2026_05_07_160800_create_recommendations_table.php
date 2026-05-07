<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attempt_id')->nullable()->constrained('tryout_attempts')->nullOnDelete();
            $table->morphs('recommendable');
            $table->enum('category', ['material', 'question']);
            $table->string('algorithm')->default('cosine_similarity');
            $table->decimal('similarity_score', 8, 4)->default(0);
            $table->text('reason');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
