<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Adding the profanity_block_type column with default value 'show'
            $table->enum('profanity_block_type', ['show', 'hide', 'hide_clickable'])->default('hide_clickable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Removing the profanity_block_type column
            $table->dropColumn('profanity_block_type');
        });
    }
};