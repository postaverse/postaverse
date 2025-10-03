<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all'; // all, admins, suspended, verified

    public function promoteUser($userId, $level)
    {
        $this->checkAdminAccess();
        
        $user = User::findOrFail($userId);
        $user->update(['admin_level' => $level]);
        
        session()->flash('message', 'User promoted successfully');
    }

    public function suspendUser($userId)
    {
        $this->checkAdminAccess();
        
        $user = User::findOrFail($userId);
        $user->update(['is_suspended' => !$user->is_suspended]);
        
        $message = $user->is_suspended ? 'User suspended' : 'User unsuspended';
        session()->flash('message', $message);
    }

    public function verifyUser($userId)
    {
        $this->checkAdminAccess();
        
        $user = User::findOrFail($userId);
        $user->update(['is_verified' => !$user->is_verified]);
        
        $message = $user->is_verified ? 'User verified' : 'User unverified';
        session()->flash('message', $message);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->checkAdminAccess();

        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('username', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filter === 'admins', function ($query) {
                $query->where('admin_level', '>', 0);
            })
            ->when($this->filter === 'suspended', function ($query) {
                $query->where('is_suspended', true);
            })
            ->when($this->filter === 'verified', function ($query) {
                $query->where('is_verified', true);
            })
            ->withCount(['posts', 'followers', 'following'])
            ->latest()
            ->paginate(20);

        return view('livewire.admin.user-management', [
            'users' => $users,
        ]);
    }

    private function checkAdminAccess()
    {
        if (!auth()->user()->admin_level) {
            abort(403, 'Unauthorized access');
        }
    }
}
