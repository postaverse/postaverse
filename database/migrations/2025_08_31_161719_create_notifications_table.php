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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('type'); // 'like', 'comment', 'follow', 'message', etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data
            
            // Related model (polymorphic)
            $table->nullableMorphs('notifiable');
            
            $table->string('action_url')->nullable(); // URL to navigate to
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'read_at']);
            $table->index(['type']);
            $table->index(['notifiable_id', 'notifiable_type']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
