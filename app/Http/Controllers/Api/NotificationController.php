<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Interaction\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of user's notifications
     */
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return NotificationResource::collection($notifications);
    }

    /**
     * Get count of unread notifications
     */
    public function unreadCount(Request $request)
    {
        $count = $request->user()
            ->notifications()
            ->whereNull('read_at')
            ->count();

        return response()->json(['unread_count' => $count]);
    }

    /**
     * Create a new notification (for testing purposes)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'link' => 'required|string'
        ]);

        $notification = Notification::create([
            'user_id' => $request->user()->id,
            'message' => $validated['message'],
            'link' => $validated['link'],
        ]);

        return new NotificationResource($notification);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->update(['read_at' => now()]);

        return new NotificationResource($notification->fresh());
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()
            ->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'All notifications marked as read']);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->update(['read_at' => null]);

        return new NotificationResource($notification->fresh());
    }

    /**
     * Bulk action for notifications
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:mark_read,mark_unread,delete',
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'integer|exists:notifications,id'
        ]);

        $notifications = Notification::whereIn('id', $validated['notification_ids'])
            ->where('user_id', $request->user()->id)
            ->get();

        if ($notifications->isEmpty()) {
            return response()->json(['message' => 'No valid notifications found'], 404);
        }

        switch ($validated['action']) {
            case 'mark_read':
                Notification::whereIn('id', $validated['notification_ids'])
                    ->where('user_id', $request->user()->id)
                    ->update(['read_at' => now()]);
                $message = 'Notifications marked as read';
                break;
                
            case 'mark_unread':
                Notification::whereIn('id', $validated['notification_ids'])
                    ->where('user_id', $request->user()->id)
                    ->update(['read_at' => null]);
                $message = 'Notifications marked as unread';
                break;
                
            case 'delete':
                Notification::whereIn('id', $validated['notification_ids'])
                    ->where('user_id', $request->user()->id)
                    ->delete();
                $message = 'Notifications deleted';
                break;
        }

        return response()->json(['message' => $message]);
    }

    /**
     * Delete a specific notification
     */
    public function destroy(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        
        if ($notification->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json(null, 204);
    }
}
