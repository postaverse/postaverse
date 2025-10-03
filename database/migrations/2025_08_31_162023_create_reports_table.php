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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            
            // Polymorphic relationship to reported content
            $table->morphs('reportable');
            
            $table->enum('reason', [
                'spam', 'harassment', 'hate_speech', 'violence', 
                'adult_content', 'copyright', 'fake_news', 'other'
            ]);
            $table->text('description')->nullable();
            
            // Review status
            $table->enum('status', ['pending', 'reviewed', 'dismissed'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('action_taken')->nullable(); // 'warning', 'content_removed', 'user_suspended', etc.
            
            $table->timestamps();
            
            // Indexes
            $table->index(['status']);
            $table->index(['reason']);
            $table->index(['reportable_id', 'reportable_type']);
            $table->index(['reporter_id']);
            $table->index(['reviewed_by']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
