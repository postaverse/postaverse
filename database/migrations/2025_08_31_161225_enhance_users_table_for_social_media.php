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
            // Remove old name column and add new structured name fields
            $table->dropColumn('name');
            
            // Core profile fields
            $table->string('username')->unique()->after('id');
            $table->string('first_name')->after('username');
            $table->string('last_name')->after('first_name');
            
            // Profile information
            $table->text('bio')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('bio');
            $table->string('cover_photo')->nullable()->after('avatar');
            $table->string('website')->nullable()->after('cover_photo');
            $table->string('location')->nullable()->after('website');
            $table->date('birth_date')->nullable()->after('location');
            $table->string('phone')->nullable()->after('birth_date');
            
            // Privacy & verification
            $table->boolean('is_private')->default(false)->after('phone');
            $table->boolean('is_verified')->default(false)->after('is_private');
            
            // Admin system (1-5 ranks, null for regular users)
            $table->tinyInteger('admin_level')->nullable()->after('is_verified');
            
            // Preferences
            $table->string('timezone')->default('UTC')->after('admin_level');
            $table->string('language')->default('en')->after('timezone');
            $table->enum('theme_preference', ['light', 'dark', 'auto'])->default('auto')->after('language');
            
            // Notification settings
            $table->boolean('email_notifications')->default(true)->after('theme_preference');
            $table->boolean('push_notifications')->default(true)->after('email_notifications');
            
            // Activity tracking
            $table->timestamp('last_active_at')->nullable()->after('push_notifications');
            $table->enum('status', ['active', 'inactive', 'suspended', 'banned'])->default('active')->after('last_active_at');
            
            // Soft deletes
            $table->softDeletes()->after('status');
            
            // Add indexes for performance
            $table->index(['admin_level']);
            $table->index(['is_verified']);
            $table->index(['status']);
            $table->index(['last_active_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add back the original name column
            $table->string('name')->after('id');
            
            // Remove all the new columns
            $table->dropIndex(['admin_level']);
            $table->dropIndex(['is_verified']);
            $table->dropIndex(['status']);
            $table->dropIndex(['last_active_at']);
            
            $table->dropSoftDeletes();
            $table->dropColumn([
                'username', 'first_name', 'last_name', 'bio', 'avatar', 'cover_photo',
                'website', 'location', 'birth_date', 'phone', 'is_private', 'is_verified',
                'admin_level', 'timezone', 'language', 'theme_preference',
                'email_notifications', 'push_notifications', 'last_active_at', 'status'
            ]);
        });
    }
};
