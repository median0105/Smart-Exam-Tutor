<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->longText('explanation');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->unsignedSmallInteger('points')->default(5);
            $table->json('tags')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->string('option_label', 10);
            $table->text('content');
            $table->boolean('is_correct')->default(false);
            $table->text('feedback')->nullable();
            $table->timestamps();
        });

        Schema::create('question_tryout', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tryout_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('position')->default(1);
            $table->timestamps();

            $table->unique(['tryout_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_tryout');
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');
    }
};
