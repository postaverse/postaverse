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
        // Add cascade deletes to posts table
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Add cascade deletes to likes table
        Schema::table('likes', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            $table->dropForeign(['user_id']);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Add cascade deletes to comments table
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            $table->dropForeign(['user_id']);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Add cascade deletes to followers table
        Schema::table('followers', function (Blueprint $table) {
            $table->dropForeign(['following_id']);
            $table->dropForeign(['follower_id']);
            $table->foreign('following_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('follower_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore original foreign keys without cascade
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            $table->dropForeign(['user_id']);
            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            $table->dropForeign(['user_id']);
            $table->foreign('post_id')->references('id')->on('posts');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('followers', function (Blueprint $table) {
            $table->dropForeign(['following_id']);
            $table->dropForeign(['follower_id']);
            $table->foreign('following_id')->references('id')->on('users');
            $table->foreign('follower_id')->references('id')->on('users');
        });
    }
};
