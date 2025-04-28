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
        // Drop text_theme_user table first due to foreign key constraints
        Schema::dropIfExists('text_theme_user');

        // Drop text_themes table
        Schema::dropIfExists('text_themes');

        // Drop meteors table
        Schema::dropIfExists('meteors');

        // Remove the meteors_last_redeemed_at column from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('meteors_last_redeemed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add meteors_last_redeemed_at column back to users table
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('meteors_last_redeemed_at')->nullable();
        });

        // Recreate meteors table
        Schema::create('meteors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });

        // Recreate text_themes table
        Schema::create('text_themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('css_class');
            $table->integer('meteorPrice');
            $table->timestamps();
        });

        // Recreate text_theme_user pivot table
        Schema::create('text_theme_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('text_theme_id')->constrained()->onDelete('cascade');
            $table->boolean('equipped')->default(false);
            $table->timestamps();
        });
    }
};
