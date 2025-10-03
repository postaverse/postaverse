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
        Schema::create('group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->enum('role', ['member', 'moderator', 'admin', 'owner'])->default('member');
            $table->timestamp('joined_at');
            $table->foreignId('invited_by')->nullable()->constrained('users');
            
            $table->timestamps();
            
            // Unique constraint
            $table->unique(['group_id', 'user_id']);
            
            // Indexes
            $table->index(['group_id', 'role']);
            $table->index(['user_id']);
            $table->index(['joined_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_members');
    }
};
