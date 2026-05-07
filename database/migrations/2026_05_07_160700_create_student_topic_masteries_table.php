<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_topic_masteries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('total_attempts')->default(0);
            $table->unsignedInteger('correct_attempts')->default(0);
            $table->unsignedInteger('wrong_attempts')->default(0);
            $table->decimal('mastery_score', 5, 2)->default(0);
            $table->decimal('weakness_score', 5, 2)->default(0);
            $table->timestamp('last_attempt_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'topic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_topic_masteries');
    }
};
