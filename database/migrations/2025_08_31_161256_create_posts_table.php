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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Content
            $table->text('content');
            $table->json('media_urls')->nullable(); // Array of media URLs
            $table->enum('media_type', ['none', 'image', 'video', 'mixed'])->default('none');
            
            // Privacy & settings
            $table->enum('visibility', ['public', 'friends', 'private'])->default('public');
            $table->string('location')->nullable();
            $table->json('tagged_users')->nullable(); // Array of user IDs
            $table->json('hashtags')->nullable(); // Array of hashtags
            
            // Post features
            $table->boolean('is_pinned')->default(false);
            $table->boolean('comments_enabled')->default(true);
            
            // Engagement metrics (cached for performance)
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);
            $table->unsignedInteger('shares_count')->default(0);
            $table->unsignedInteger('views_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index(['user_id', 'created_at']);
            $table->index(['visibility']);
            $table->index(['is_pinned']);
            $table->index(['likes_count']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
