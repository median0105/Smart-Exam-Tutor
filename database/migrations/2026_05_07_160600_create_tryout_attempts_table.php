<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tryout_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tryout_id')->constrained()->cascadeOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->decimal('score', 5, 2)->default(0);
            $table->unsignedSmallInteger('correct_answers')->default(0);
            $table->unsignedSmallInteger('wrong_answers')->default(0);
            $table->unsignedSmallInteger('unanswered_answers')->default(0);
            $table->string('detected_level')->nullable();
            $table->text('automated_feedback')->nullable();
            $table->text('study_advice')->nullable();
            $table->json('weakness_vector')->nullable();
            $table->json('knn_snapshot')->nullable();
            $table->timestamps();
        });

        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('tryout_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_option_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('topic_id')->constrained()->cascadeOnDelete();
            $table->enum('difficulty', ['easy', 'medium', 'hard']);
            $table->boolean('is_correct')->default(false);
            $table->unsignedSmallInteger('points_earned')->default(0);
            $table->unsignedInteger('time_spent_seconds')->default(0);
            $table->json('answer_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_answers');
        Schema::dropIfExists('tryout_attempts');
    }
};
