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
        // Add indexes to posts table
        Schema::table('posts', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
        });

        // Add indexes to blogs table
        Schema::table('blogs', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
        });

        // Add indexes to comments table
        Schema::table('comments', function (Blueprint $table) {
            $table->index('post_id');
            $table->index('user_id');
            $table->index(['post_id', 'created_at']);
        });

        // Add indexes to blog_comments table
        Schema::table('blog_comments', function (Blueprint $table) {
            $table->index('blog_id');
            $table->index('user_id');
            $table->index(['blog_id', 'created_at']);
        });

        // Add indexes to likes table
        Schema::table('likes', function (Blueprint $table) {
            $table->index(['post_id', 'user_id']);
        });

        // Add indexes to blog_likes table
        Schema::table('blog_likes', function (Blueprint $table) {
            $table->index(['blog_id', 'user_id']);
        });

        // Add indexes to followers table
        Schema::table('followers', function (Blueprint $table) {
            $table->index(['follower_id', 'following_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from posts table
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['user_id', 'created_at']);
        });

        // Remove indexes from blogs table
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['user_id', 'created_at']);
        });

        // Remove indexes from comments table
        Schema::table('comments', function (Blueprint $table) {
            $table->dropIndex(['post_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['post_id', 'created_at']);
        });

        // Remove indexes from blog_comments table
        Schema::table('blog_comments', function (Blueprint $table) {
            $table->dropIndex(['blog_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['blog_id', 'created_at']);
        });

        // Remove indexes from likes table
        Schema::table('likes', function (Blueprint $table) {
            $table->dropIndex(['post_id', 'user_id']);
        });

        // Remove indexes from blog_likes table
        Schema::table('blog_likes', function (Blueprint $table) {
            $table->dropIndex(['blog_id', 'user_id']);
        });

        // Remove indexes from followers table
        Schema::table('followers', function (Blueprint $table) {
            $table->dropIndex(['follower_id', 'following_id']);
        });
    }
};
