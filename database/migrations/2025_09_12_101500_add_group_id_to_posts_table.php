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
        // Only add the column if it doesn't already exist (idempotent/multiple runs safe)
        if (!Schema::hasColumn('posts', 'group_id')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete()->after('user_id');
                $table->index('group_id');
            });
        }

        // If there are any group_posts, migrate them into posts
        if (Schema::hasTable('group_posts') && Schema::hasTable('posts')) {
            $groupPosts = \Illuminate\Support\Facades\DB::table('group_posts')->get();
            foreach ($groupPosts as $gp) {
                // Map fields conservatively
                $mediaUrls = null;
                if (!empty($gp->image_path)) {
                    $mediaUrls = json_encode([$gp->image_path]);
                }

                // Some older rows may not have deleted_at; handle gracefully
                $deletedAt = property_exists($gp, 'deleted_at') ? $gp->deleted_at : null;

                \Illuminate\Support\Facades\DB::table('posts')->insert([
                    'user_id' => $gp->user_id,
                    'content' => $gp->content,
                    'media_urls' => $mediaUrls,
                    'media_type' => !empty($gp->image_path) ? 'image' : 'none',
                    'visibility' => 'public',
                    'location' => null,
                    'tagged_users' => null,
                    'hashtags' => null,
                    'is_pinned' => $gp->is_pinned,
                    'comments_enabled' => !$gp->is_locked,
                    'likes_count' => $gp->upvotes ?? 0,
                    'comments_count' => $gp->comments_count ?? 0,
                    'shares_count' => 0,
                    'views_count' => 0,
                    'created_at' => $gp->created_at,
                    'updated_at' => $gp->updated_at,
                    'deleted_at' => $deletedAt,
                    'group_id' => $gp->group_id,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropIndex(['group_id']);
            $table->dropColumn('group_id');
        });
    }
};
