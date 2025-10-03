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
        Schema::create('hashtags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedInteger('posts_count')->default(0);
            $table->decimal('trending_score', 8, 2)->default(0);
            $table->boolean('is_trending')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index(['is_trending', 'trending_score']);
            $table->index(['posts_count']);
            $table->index(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hashtags');
    }
};
