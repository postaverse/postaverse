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
        Schema::create('banned_ips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banned_id');
            $table->string('ip_address');
            $table->timestamps();
            
            $table->foreign('banned_id')->references('id')->on('banned')->onDelete('cascade');
            $table->index(['ip_address']);
            $table->unique(['banned_id', 'ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banned_ips');
    }
};
