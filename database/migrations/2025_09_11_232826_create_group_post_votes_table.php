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
        Schema::create('group_post_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('vote', ['up', 'down']);
            $table->timestamps();
            
            // Unique constraint to prevent duplicate votes
            $table->unique(['group_post_id', 'user_id']);
            $table->index(['group_post_id', 'vote']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_post_votes');
    }
};
