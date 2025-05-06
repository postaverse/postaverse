<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User\BlockedUser;
use App\Models\User\User;

class BlockedUsers extends Component
{
    public $username = '';
    public $blockedUsersList = [];

    public function mount()
    {
        $this->loadBlockedUsers();
    }

    public function loadBlockedUsers()
    {
        $blockedUsers = BlockedUser::where('user_id', auth()->id())->pluck('blocked_users')->first() ?? '';
        
        // If we have blocked user IDs, convert them to usernames
        if (!empty($blockedUsers)) {
            $blockedIds = array_map('trim', explode(',', $blockedUsers));
            $this->blockedUsersList = User::whereIn('id', $blockedIds)
                ->select('id', 'handle')
                ->get()
                ->toArray();
        } else {
            $this->blockedUsersList = [];
        }
    }

    public function addUser()
    {
        $this->validate([
            'username' => 'required|exists:users,handle',
        ], [
            'username.required' => 'Please enter a username.',
            'username.exists' => 'This username does not exist.'
        ]);
        
        // Get user ID from username
        $user = User::where('handle', $this->username)->first();
        
        if (!$user) {
            $this->addError('username', 'User not found.');
            return;
        }

        // Check if this user is already blocked
        $isAlreadyBlocked = collect($this->blockedUsersList)->contains(function ($blockedUser) use ($user) {
            return isset($blockedUser['id']) && $blockedUser['id'] == $user->id;
        });

        if ($isAlreadyBlocked) {
            $this->addError('username', 'This user is already blocked.');
            return;
        }
        
        // Add the user to the blocked list
        $this->blockedUsersList[] = [
            'id' => $user->id,
            'handle' => $user->handle
        ];
        
        // Save to database
        $this->saveBlockedList();
        
        // Clear the input
        $this->username = '';
    }
    
    public function removeUser($userId)
    {
        // Filter out the user we want to remove
        $this->blockedUsersList = array_filter($this->blockedUsersList, function ($user) use ($userId) {
            return $user['id'] != $userId;
        });
        
        // Re-index the array
        $this->blockedUsersList = array_values($this->blockedUsersList);
        
        // Save to database
        $this->saveBlockedList();
    }
    
    protected function saveBlockedList()
    {
        // Extract IDs from the blocked users list
        $userIds = collect($this->blockedUsersList)->pluck('id')->implode(',');
        
        // Remove the database record if it exists
        BlockedUser::where('user_id', auth()->id())->delete();

        // Create new record only if there are user IDs to block
        if (!empty($userIds)) {
            BlockedUser::create([
                'user_id' => auth()->id(),
                'blocked_users' => $userIds,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.User.blocked-users');
    }
}