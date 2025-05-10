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
        // Check if the table doesn't already exist
        if (!Schema::hasTable('followers')) {
            Schema::create('followers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('follower_id');
                $table->unsignedBigInteger('following_id');
                $table->timestamps();
                
                $table->foreign('follower_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('following_id')->references('id')->on('users')->onDelete('cascade');
                
                // Prevent duplicate follows
                $table->unique(['follower_id', 'following_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followers');
    }
};
