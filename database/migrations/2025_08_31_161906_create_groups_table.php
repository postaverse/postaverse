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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('avatar')->nullable();
            $table->string('cover_photo')->nullable();
            
            $table->enum('privacy', ['public', 'private', 'secret'])->default('public');
            $table->foreignId('created_by')->constrained('users');
            
            // Metrics
            $table->unsignedInteger('members_count')->default(0);
            $table->unsignedInteger('posts_count')->default(0);
            
            $table->boolean('is_verified')->default(false);
            $table->string('category')->nullable();
            $table->json('rules')->nullable(); // Group rules as array
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['privacy']);
            $table->index(['category']);
            $table->index(['is_verified']);
            $table->index(['created_by']);
            $table->index(['members_count']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
