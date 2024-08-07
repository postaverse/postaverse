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
        Schema::table('users', function (Blueprint $table) {
            // Change 'admin_rank >= 1' boolean column to 'admin_rank' integer column
            $table->integer('admin_rank')->default(0); // 0 = non, 1 = junior, 2 = admin, 3 = senior, 4 = owner
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('admin_rank');
        });
    }
};