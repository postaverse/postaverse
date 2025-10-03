<?php

namespace App\Livewire\Social;

use App\Models\Notification;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Notifications extends Component
{
    use WithPagination;

    public $filter = 'all'; // all, unread, read

    public function markAsRead($notificationId)
    {
        auth()->user()->notifications()
            ->where('id', $notificationId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        session()->flash('message', 'All notifications marked as read');
    }

    public function deleteNotification($notificationId)
    {
        auth()->user()->notifications()
            ->where('id', $notificationId)
            ->delete();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $notifications = auth()->user()->notifications()
            ->when($this->filter === 'unread', function ($query) {
                return $query->whereNull('read_at');
            })
            ->when($this->filter === 'read', function ($query) {
                return $query->whereNotNull('read_at');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = auth()->user()->notifications()->whereNull('read_at')->count();

        return view('livewire.social.notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
}
