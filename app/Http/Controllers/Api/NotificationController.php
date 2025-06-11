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
     * Delete a notification
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
