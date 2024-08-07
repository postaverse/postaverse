<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('text_theme_user', function (Blueprint $table) {
            $table->boolean('equipped')->default(false); // Add equipped column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('text_theme_user', function (Blueprint $table) {
            $table->dropColumn('equipped'); // Drop equipped column
        });
    }
};
