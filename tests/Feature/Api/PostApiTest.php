<?php

namespace Tests\Feature\Api;

use App\Models\User\User;
use App\Models\Post\Post;
use App\Models\Post\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PostApiTest extends TestCase
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
    }

    public function test_guest_cannot_access_posts_index()
    {
        $response = $this->getJson('/api/posts');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_get_posts_index()
    {
        // Create some posts
        $post1 = new Post([
            'title' => 'Test Post 1',
            'content' => 'Content of test post 1',
        ]);
        $post1->user()->associate($this->user);
        $post1->save();

        $post2 = new Post([
            'title' => 'Test Post 2', 
            'content' => 'Content of test post 2',
        ]);
        $post2->user()->associate($this->otherUser);
        $post2->save();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/posts');

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
                    ],
                    'links',
                    'meta'
                ]);
    }

    public function test_user_can_create_post()
    {
        $postData = [
            'title' => 'New Test Post',
            'content' => 'This is the content of the new test post'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/posts', $postData);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'title' => 'New Test Post',
                    'content' => 'This is the content of the new test post'
                ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'New Test Post',
            'user_id' => $this->user->id
        ]);
    }

    public function test_post_creation_requires_title()
    {
        $postData = [
            'content' => 'This is content without a title'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/posts', $postData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['title']);
    }

    public function test_post_creation_requires_content()
    {
        $postData = [
            'title' => 'Title without content'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/posts', $postData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['content']);
    }

    public function test_user_can_view_specific_post()
    {
        $post = new Post([
            'title' => 'Specific Test Post',
            'content' => 'Content of specific test post',
        ]);
        $post->user()->associate($this->user);
        $post->save();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'id' => $post->id,
                    'title' => 'Specific Test Post',
                    'content' => 'Content of specific test post'
                ]);
    }

    public function test_user_can_update_own_post()
    {
        $post = new Post([
            'title' => 'Original Title',
            'content' => 'Original content',
        ]);
        $post->user()->associate($this->user);
        $post->save();

        $updateData = [
            'title' => 'Updated Title',
            'content' => 'Updated content'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'title' => 'Updated Title',
                    'content' => 'Updated content'
                ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'content' => 'Updated content'
        ]);
    }

    public function test_user_cannot_update_others_post()
    {
        $post = new Post([
            'title' => 'Other User Post',
            'content' => 'Content by other user',
        ]);
        $post->user()->associate($this->otherUser);
        $post->save();

        $updateData = [
            'title' => 'Trying to update',
            'content' => 'Trying to update content'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/posts/{$post->id}", $updateData);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_post()
    {
        $post = new Post([
            'title' => 'Post to delete',
            'content' => 'This post will be deleted',
        ]);
        $post->user()->associate($this->user);
        $post->save();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id
        ]);
    }

    public function test_user_cannot_delete_others_post()
    {
        $post = new Post([
            'title' => 'Other User Post',
            'content' => 'Content by other user',
        ]);
        $post->user()->associate($this->otherUser);
        $post->save();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id
        ]);
    }

    public function test_user_can_like_post()
    {
        $post = new Post([
            'title' => 'Post to like',
            'content' => 'This post will be liked',
        ]);
        $post->user()->associate($this->otherUser);
        $post->save();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/posts/{$post->id}/like");

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'liked' => true
                ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'post_id' => $post->id
        ]);
    }

    public function test_user_can_unlike_post()
    {
        $post = new Post([
            'title' => 'Post to unlike',
            'content' => 'This post will be unliked',
        ]);
        $post->user()->associate($this->otherUser);
        $post->save();

        // First like the post
        Like::create([
            'user_id' => $this->user->id,
            'post_id' => $post->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/posts/{$post->id}/like");

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'liked' => false
                ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $this->user->id,
            'post_id' => $post->id
        ]);
    }

    public function test_user_can_get_feed()
    {
        // Create posts from different users
        $post1 = new Post([
            'title' => 'Feed Post 1',
            'content' => 'Content 1',
        ]);
        $post1->user()->associate($this->user);
        $post1->save();

        $post2 = new Post([
            'title' => 'Feed Post 2',
            'content' => 'Content 2',
        ]);
        $post2->user()->associate($this->otherUser);
        $post2->save();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/feed');

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
    }

    public function test_nonexistent_post_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/posts/999999');

        $response->assertStatus(404);
    }

    public function test_posts_are_ordered_by_latest_first()
    {
        $firstPost = new Post([
            'title' => 'First Post',
            'content' => 'Created first',
        ]);
        $firstPost->user()->associate($this->user);
        $firstPost->save();

        // Sleep to ensure timestamp difference
        sleep(1);

        $secondPost = new Post([
            'title' => 'Second Post',
            'content' => 'Created second',
        ]);
        $secondPost->user()->associate($this->user);
        $secondPost->save();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/posts');

        $response->assertStatus(200);
        
        $posts = $response->json('data');
        // Check that posts are ordered by latest first (most recent first)
        $this->assertGreaterThan(
            strtotime($firstPost->created_at), 
            strtotime($posts[0]['created_at'])
        );
        $this->assertEquals($secondPost->title, $posts[0]['title']);
        $this->assertEquals($firstPost->title, $posts[1]['title']);
    }
}
