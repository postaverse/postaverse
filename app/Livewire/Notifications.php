<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use Livewire\WithPagination;

class Notifications extends Component
{
    use WithPagination;
    
    public $selectedNotifications = [];
    public $selectAll = false;
    public $bulkActionType = '';
    
    // Listen for events
    protected $listeners = ['refreshNotifications' => '$refresh'];
    
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedNotifications = Notification::where('user_id', auth()->id())
                ->pluck('id')
                ->map(function ($id) {
                    return (string) $id;
                })
                ->toArray();
        } else {
            $this->selectedNotifications = [];
        }
    }
    
    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id == auth()->id()) {
            $notification->markAsRead();
            $this->dispatch('refreshNotifications');
        }
    }
    
    public function markAsUnread($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id == auth()->id()) {
            $notification->update(['read_at' => null]);
            $this->dispatch('refreshNotifications');
        }
    }
    
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        $this->dispatch('refreshNotifications');
    }
    
    public function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id == auth()->id()) {
            $notification->delete();
            $this->dispatch('refreshNotifications');
        }
    }
    
    public function executeBulkAction()
    {
        if (empty($this->selectedNotifications)) {
            return;
        }
        
        switch ($this->bulkActionType) {
            case 'mark_read':
                Notification::whereIn('id', $this->selectedNotifications)
                    ->where('user_id', auth()->id())
                    ->update(['read_at' => now()]);
                break;
                
            case 'mark_unread':
                Notification::whereIn('id', $this->selectedNotifications)
                    ->where('user_id', auth()->id())
                    ->update(['read_at' => null]);
                break;
                
            case 'delete':
                Notification::whereIn('id', $this->selectedNotifications)
                    ->where('user_id', auth()->id())
                    ->delete();
                break;
        }
        
        $this->selectedNotifications = [];
        $this->selectAll = false;
        $this->bulkActionType = '';
        $this->dispatch('refreshNotifications');
    }
    
    public function render()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);
            
        // Count of unread notifications
        $unreadCount = Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->count();
        
        return view('livewire.notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ])->layout('layouts.app');
    }
}
