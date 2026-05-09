<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('external_source')->nullable()->after('id');
            $table->string('external_id')->nullable()->after('external_source');
            $table->index(['external_source', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['external_source', 'external_id']);
            $table->dropColumn(['external_source', 'external_id']);
        });
    }
};
