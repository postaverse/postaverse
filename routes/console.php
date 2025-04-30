<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('add-admin', function () {
    // Prompt for user id
    $userId = $this->ask('Enter the user ID to add as admin');
    // Attach the user to the admin role
    $user = User::find($userId);
    if ($user) {
        User::where('id', $userId)->update(['admin_rank' => 4]);
        $this->info("User with ID {$userId} has been added as admin.");
    } else {
        $this->error("User with ID {$userId} not found.");
    }
})->purpose('Add a user as admin');
Artisan::command('remove-admin', function () {
    // Prompt for user id
    $userId = $this->ask('Enter the user ID to remove from admin');
    // Detach the user from the admin role
    $user = User::find($userId);
    if ($user) {
        User::where('id', $userId)->update(['admin_rank' => 0]);
        $this->info("User with ID {$userId} has been removed from admin.");
    } else {
        $this->error("User with ID {$userId} not found.");
    }
})->purpose('Remove a user from admin');
