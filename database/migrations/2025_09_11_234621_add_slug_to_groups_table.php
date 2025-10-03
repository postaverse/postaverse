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
        Schema::table('groups', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name')->unique();
        });

        // Populate slug for existing groups
        if (Schema::hasTable('groups')) {
            \Illuminate\Support\Facades\DB::table('groups')->get()->each(function ($group) {
                $slug = \Illuminate\Support\Str::slug($group->name);
                // ensure uniqueness
                $base = $slug;
                $i = 1;
                while (\Illuminate\Support\Facades\DB::table('groups')->where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                \Illuminate\Support\Facades\DB::table('groups')->where('id', $group->id)->update(['slug' => $slug]);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
