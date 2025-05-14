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
        Schema::dropIfExists('badges');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the badge
            $table->text('description')->nullable(); // Description of the badge
            $table->string('icon')->nullable(); // Path to the badge icon
            $table->timestamps();
        });
    }
};
