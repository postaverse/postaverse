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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->string('conversation_id')->index(); // Group messages by conversation
            
            $table->text('content');
            $table->string('media_url')->nullable();
            $table->enum('media_type', ['none', 'image', 'video', 'file'])->default('none');
            $table->enum('message_type', ['text', 'media', 'system'])->default('text');
            
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['sender_id', 'recipient_id']);
            $table->index(['conversation_id', 'created_at']);
            $table->index(['read_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
