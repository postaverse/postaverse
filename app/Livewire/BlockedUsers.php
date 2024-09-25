<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BlockedUser;

class BlockedUsers extends Component
{
    public $userIds;

    public function mount()
    {
        $this->userIds = BlockedUser::where('user_id', auth()->id())->pluck('blocked_users')->first() ?? '';
    }

    public function submit()
    {
        // Ensure userIds is not null
        $blockedUsers = $this->userIds ?? '';

        // Strip any whitespace
        $blockedUsers = preg_replace('/\s+/', '', $blockedUsers);

        // Remove the database record if it exists
        BlockedUser::where('user_id', auth()->id())->delete();

        BlockedUser::create([
            'user_id' => auth()->id(),
            'blocked_users' => $blockedUsers,
        ]);
    }

    public function render()
    {
        return view('livewire.blocked-users');
    }
}