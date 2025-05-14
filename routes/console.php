<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User\User;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Command to add/remove a user as admin
Artisan::command('admin', function () {
    // Prompt for user id
    $userId = $this->ask('Enter the user ID to add as admin');
    
    // Prompt for admin rank
    $rank = $this->ask('Enter admin rank (0-5)');
    
    // Validate the rank
    if (!is_numeric($rank) || $rank < 0 || $rank > 5) {
        $this->error('Rank must be a number between 0 and 5.');
        return;
    }
    
    // Attach the user to the admin role with specified rank
    $user = User::find($userId);
    if ($user) {
        User::where('id', $userId)->update(['admin_rank' => $rank]);
        $this->info("User with ID {$userId} has been added as admin with rank {$rank}.");
    } else {
        $this->error("User with ID {$userId} not found.");
    }
})->purpose('Add a user as admin');

// Command to truncate the database and reseed
Artisan::command('prep', function () {
    // Prompt for confirmation
    if ($this->confirm('Are you sure you want to truncate the database and reseed?')) {
        // Truncate all tables
        $this->call('migrate:fresh');
        
        // Reseed the database
        $this->call('db:seed');
        
        $this->info('Database has been truncated and reseeded.');
    } else {
        $this->info('Operation cancelled.');
    }
})->purpose('Truncate the database and reseed');
