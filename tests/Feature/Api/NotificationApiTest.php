<?php

namespace Tests\Feature\Api;

use App\Models\User\User;
use App\Models\Interaction\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class NotificationApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $otherUser;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'handle' => 'testuser',
            'email_verified_at' => now(),
        ]);

        $this->otherUser = User::create([
            'name' => 'Other User',
            'email' => 'other@example.com',
            'password' => Hash::make('password123'),
            'handle' => 'otheruser',
            'email_verified_at' => now(),
        ]);

        $this->token = $this->user->createToken('Test Token')->plainTextToken;

        // Create test notifications
        $this->createTestNotifications();
    }

    private function createTestNotifications()
    {
        // Create some notifications for the user
        Notification::create([
            'user_id' => $this->user->id,
            'message' => 'John Doe liked your post',
            'link' => '/posts/1'
        ]);

        Notification::create([
            'user_id' => $this->user->id,
            'message' => 'Jane Smith commented on your post',
            'link' => '/posts/1'
        ]);

        Notification::create([
            'user_id' => $this->user->id,
            'message' => 'Alex Johnson started following you',
            'link' => '/profile/3',
            'read_at' => now()
        ]);
    }

    public function test_guest_cannot_access_notifications()
    {
        $response = $this->getJson('/api/notifications');

        $response->assertStatus(401);
    }

    public function test_user_can_get_notifications()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/notifications');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'message',
                            'link',
                            'read_at',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                    'links',
                    'meta'
                ]);

        $notifications = $response->json('data');
        $this->assertCount(3, $notifications);
    }

    public function test_notifications_are_ordered_by_latest_first()
    {
        // Sleep briefly to ensure timestamp difference
        sleep(1);
        
        // Create a newer notification
        $newerNotification = Notification::create([
            'user_id' => $this->user->id,
            'message' => 'Mike Wilson liked your new post',
            'link' => '/posts/2',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/notifications');

        $response->assertStatus(200);

        $notifications = $response->json('data');
        $this->assertEquals($newerNotification->id, $notifications[0]['id']);
    }

    public function test_user_can_get_unread_count()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/notifications/unread-count');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'unread_count'
                ]);

        $this->assertEquals(2, $response->json('unread_count')); // 2 unread notifications
    }

    public function test_user_can_mark_notification_as_read()
    {
        $notification = Notification::where('user_id', $this->user->id)
                                  ->whereNull('read_at')
                                  ->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/notifications/{$notification->id}/read");

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'id' => $notification->id
                ]);

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
        ]);

        // Check that read_at is not null
        $updatedNotification = Notification::find($notification->id);
        $this->assertNotNull($updatedNotification->read_at);
    }

    public function test_user_cannot_mark_others_notification_as_read()
    {
        // Create a notification for other user
        $otherNotification = Notification::create([
            'user_id' => $this->otherUser->id,
            'message' => 'Someone liked your post',
            'link' => '/posts/1'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/notifications/{$otherNotification->id}/read");

        $response->assertStatus(403);
    }

    public function test_user_can_mark_all_notifications_as_read()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/notifications/read-all');

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'message' => 'All notifications marked as read'
                ]);

        // Verify all user's notifications are marked as read
        $unreadCount = Notification::where('user_id', $this->user->id)
                                 ->whereNull('read_at')
                                 ->count();
        $this->assertEquals(0, $unreadCount);
    }

    public function test_user_can_delete_notification()
    {
        $notification = Notification::where('user_id', $this->user->id)->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/notifications/{$notification->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('notifications', [
            'id' => $notification->id
        ]);
    }

    public function test_user_cannot_delete_others_notification()
    {
        // Create a notification for other user
        $otherNotification = Notification::create([
            'user_id' => $this->otherUser->id,
            'message' => 'Someone liked your post',
            'link' => '/posts/1'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/notifications/{$otherNotification->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('notifications', [
            'id' => $otherNotification->id
        ]);
    }

    public function test_nonexistent_notification_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/notifications/999999/read');

        $response->assertStatus(404);
    }

    public function test_notifications_pagination()
    {
        // Create many notifications
        for ($i = 1; $i <= 25; $i++) {
            Notification::create([
                'user_id' => $this->user->id,
                'message' => "Test notification $i",
                'link' => "/posts/$i"
            ]);
        }

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/notifications?page=1');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data',
                    'links' => [
                        'first',
                        'last',
                        'prev',
                        'next'
                    ],
                    'meta' => [
                        'current_page',
                        'last_page',
                        'per_page',
                        'total'
                    ]
                ]);

        $meta = $response->json('meta');
        $this->assertGreaterThan(1, $meta['last_page']);
    }

    public function test_unread_count_updates_after_marking_as_read()
    {
        // Get initial unread count
        $initialResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/notifications/unread-count');

        $initialCount = $initialResponse->json('unread_count');

        // Mark one as read
        $notification = Notification::where('user_id', $this->user->id)
                                  ->whereNull('read_at')
                                  ->first();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/notifications/{$notification->id}/read");

        // Get updated unread count
        $updatedResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/notifications/unread-count');

        $updatedCount = $updatedResponse->json('unread_count');

        $this->assertEquals($initialCount - 1, $updatedCount);
    }

    public function test_mark_all_as_read_makes_unread_count_zero()
    {
        // Mark all as read
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/notifications/read-all');

        // Check unread count
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/notifications/unread-count');

        $response->assertStatus(200)
                ->assertJson([
                    'unread_count' => 0
                ]);
    }
}
