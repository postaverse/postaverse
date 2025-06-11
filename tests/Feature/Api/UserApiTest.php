<?php

namespace Tests\Feature\Api;

use App\Models\User\User;
use App\Models\Post\Post;
use App\Models\Blog\Blog;
use App\Models\Interaction\Follower;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $otherUser;
    private $thirdUser;
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
            'bio' => 'Test user bio'
        ]);

        $this->otherUser = User::create([
            'name' => 'Other User',
            'email' => 'other@example.com',
            'password' => Hash::make('password123'),
            'handle' => 'otheruser',
            'email_verified_at' => now(),
            'bio' => 'Other user bio'
        ]);

        $this->thirdUser = User::create([
            'name' => 'Third User',
            'email' => 'third@example.com',
            'password' => Hash::make('password123'),
            'handle' => 'thirduser',
            'email_verified_at' => now(),
        ]);

        $this->token = $this->user->createToken('Test Token')->plainTextToken;
    }

    public function test_guest_cannot_access_users_index()
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_get_users_list()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/users');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'handle',
                            'bio',
                            'profile_photo_url',
                            'verified',
                            'site_verified',
                            'followers_count',
                            'following_count',
                            'posts_count',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                    'links',
                    'meta'
                ]);
    }

    public function test_user_can_view_specific_user()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/users/{$this->otherUser->id}");

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'id' => $this->otherUser->id,
                    'name' => 'Other User',
                    'handle' => 'otheruser',
                    'bio' => 'Other user bio'
                ]);
    }

    public function test_nonexistent_user_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/users/999999');

        $response->assertStatus(404);
    }

    public function test_user_can_follow_another_user()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/users/{$this->otherUser->id}/follow");

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'following' => true,
                    'message' => 'Successfully followed user'
                ]);

        $this->assertDatabaseHas('followers', [
            'follower_id' => $this->user->id,
            'following_id' => $this->otherUser->id
        ]);
    }

    public function test_user_can_unfollow_another_user()
    {
        // First create a follow relationship
        Follower::create([
            'follower_id' => $this->user->id,
            'following_id' => $this->otherUser->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/users/{$this->otherUser->id}/follow");

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'following' => false,
                    'message' => 'Successfully unfollowed user'
                ]);

        $this->assertDatabaseMissing('followers', [
            'follower_id' => $this->user->id,
            'following_id' => $this->otherUser->id
        ]);
    }

    public function test_user_cannot_follow_themselves()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/users/{$this->user->id}/follow");

        $response->assertStatus(400)
                ->assertJsonFragment([
                    'message' => 'You cannot follow yourself'
                ]);
    }

    public function test_user_can_get_followers_list()
    {
        // Create some followers for otherUser
        Follower::create([
            'follower_id' => $this->user->id,
            'following_id' => $this->otherUser->id
        ]);

        Follower::create([
            'follower_id' => $this->thirdUser->id,
            'following_id' => $this->otherUser->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/users/{$this->otherUser->id}/followers");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'handle',
                            'bio',
                            'profile_photo_url',
                            'verified',
                            'site_verified',
                            'followers_count',
                            'following_count',
                            'posts_count',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);

        $followers = $response->json('data');
        $this->assertCount(2, $followers);
    }

    public function test_user_can_get_following_list()
    {
        // Create some following relationships for user
        Follower::create([
            'follower_id' => $this->user->id,
            'following_id' => $this->otherUser->id
        ]);

        Follower::create([
            'follower_id' => $this->user->id,
            'following_id' => $this->thirdUser->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/users/{$this->user->id}/following");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'handle',
                            'bio',
                            'profile_photo_url',
                            'verified',
                            'site_verified',
                            'followers_count',
                            'following_count',
                            'posts_count',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);

        $following = $response->json('data');
        $this->assertCount(2, $following);
    }

    public function test_user_can_get_user_posts()
    {
        // Create some posts for otherUser
        Post::create([
            'title' => 'User Post 1',
            'content' => 'Content of user post 1',
            'user_id' => $this->otherUser->id,
        ]);

        Post::create([
            'title' => 'User Post 2',
            'content' => 'Content of user post 2',
            'user_id' => $this->otherUser->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/users/{$this->otherUser->id}/posts");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'content',
                            'user',
                            'likes_count',
                            'comments_count',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);

        $posts = $response->json('data');
        $this->assertCount(2, $posts);
        $this->assertEquals('User Post 2', $posts[0]['title']); // Latest first
        $this->assertEquals('User Post 1', $posts[1]['title']);
    }

    public function test_user_can_get_user_blogs()
    {
        // Create some blogs for otherUser
        Blog::create([
            'title' => 'User Blog 1',
            'content' => 'Content of user blog 1',
            'user_id' => $this->otherUser->id,
        ]);

        Blog::create([
            'title' => 'User Blog 2',
            'content' => 'Content of user blog 2',
            'user_id' => $this->otherUser->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/users/{$this->otherUser->id}/blogs");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'content',
                            'user',
                            'likes_count',
                            'comments_count',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]);

        $blogs = $response->json('data');
        $this->assertCount(2, $blogs);
    }

    public function test_user_stats_are_calculated_correctly()
    {
        // Create followers for user
        Follower::create([
            'follower_id' => $this->otherUser->id,
            'following_id' => $this->user->id
        ]);

        Follower::create([
            'follower_id' => $this->thirdUser->id,
            'following_id' => $this->user->id
        ]);

        // Create following for user
        Follower::create([
            'follower_id' => $this->user->id,
            'following_id' => $this->otherUser->id
        ]);

        // Create posts for user
        Post::create([
            'title' => 'User Post',
            'content' => 'Content',
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/users/{$this->user->id}");

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'followers_count' => 2,
                    'following_count' => 1,
                    'posts_count' => 1
                ]);
    }

    public function test_users_list_can_be_paginated()
    {
        // Create additional users to test pagination
        for ($i = 1; $i <= 25; $i++) {
            User::create([
                'name' => "Test User $i",
                'email' => "test$i@example.com",
                'password' => Hash::make('password123'),
                'handle' => "testuser$i",
                'email_verified_at' => now(),
            ]);
        }

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/users?page=1');

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
                        'from',
                        'last_page',
                        'per_page',
                        'to',
                        'total'
                    ]
                ]);

        $meta = $response->json('meta');
        $this->assertGreaterThan(1, $meta['last_page']);
    }

    public function test_empty_followers_list_returns_empty_array()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/users/{$this->user->id}/followers");

        $response->assertStatus(200)
                ->assertJson([
                    'data' => []
                ]);
    }

    public function test_empty_following_list_returns_empty_array()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/users/{$this->user->id}/following");

        $response->assertStatus(200)
                ->assertJson([
                    'data' => []
                ]);
    }
}
