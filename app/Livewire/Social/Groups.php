<?php

namespace App\Livewire\Social;

use App\Models\Group;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Groups extends Component
{
    use WithPagination;

    public $search = '';
    public $showCreateModal = false;
    public $groupName = '';
    public $groupDescription = '';
    public $isPrivate = false;

    public function createGroup()
    {
        $this->validate([
            'groupName' => 'required|string|max:255',
            'groupDescription' => 'required|string|max:1000',
        ]);

        $group = Group::create([
            'name' => $this->groupName,
            'description' => $this->groupDescription,
            'created_by' => auth()->id(),
            'privacy' => $this->isPrivate ? 'private' : 'public',
        ]);

        // Add creator as admin
        $group->members()->attach(auth()->id(), [
            'role' => 'admin',
            'joined_at' => now(),
        ]);

        $this->reset(['groupName', 'groupDescription', 'isPrivate', 'showCreateModal']);
        session()->flash('message', 'Group created successfully!');
    }

    public function joinGroup($groupId)
    {
        $group = Group::findOrFail($groupId);
        
        if (!$group->members()->where('user_id', auth()->id())->exists()) {
            $group->members()->attach(auth()->id(), [
                'role' => 'member',
                'joined_at' => now(),
            ]);
            session()->flash('message', 'Joined group successfully!');
        }
    }

    public function leaveGroup($groupId)
    {
        $group = Group::findOrFail($groupId);
        $group->members()->detach(auth()->id());
        session()->flash('message', 'Left group successfully!');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $myGroups = auth()->user()->groups()
            ->withCount('members')
            ->get();

        $allGroups = Group::when($this->search, function ($query) {
                return $query->where('name', 'like', '%' . $this->search . '%')
                           ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->where('is_private', false)
            ->withCount('members')
            ->paginate(12);

        return view('livewire.social.groups', [
            'myGroups' => $myGroups,
            'allGroups' => $allGroups,
        ]);
    }
}
