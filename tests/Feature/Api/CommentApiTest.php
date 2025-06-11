<?php

namespace Tests\Feature\Api;

use App\Models\User\User;
use App\Models\Post\Post;
use App\Models\Blog\Blog;
use App\Models\Post\Comment as PostComment;
use App\Models\Blog\BlogComment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CommentApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $otherUser;
    private $token;
    private $post;
    private $blog;

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

        $this->post = new Post([
            'title' => 'Test Post',
            'content' => 'Content of test post',
        ]);
        $this->post->user()->associate($this->otherUser);
        $this->post->save();

        $this->blog = new Blog([
            'title' => 'Test Blog',
            'content' => 'Content of test blog',
        ]);
        $this->blog->user()->associate($this->otherUser);
        $this->blog->save();
    }

    public function test_guest_cannot_create_post_comment()
    {
        $commentData = [
            'content' => 'This is a test comment'
        ];

        $response = $this->postJson("/api/posts/{$this->post->id}/comments", $commentData);

        $response->assertStatus(401);
    }

    public function test_user_can_create_post_comment()
    {
        $commentData = [
            'content' => 'This is a test comment on a post'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/posts/{$this->post->id}/comments", $commentData);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'content' => 'This is a test comment on a post'
                ]);

        $this->assertDatabaseHas('comments', [
            'content' => 'This is a test comment on a post',
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);
    }

    public function test_user_can_create_blog_comment()
    {
        $commentData = [
            'content' => 'This is a test comment on a blog'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/blogs/{$this->blog->id}/comments", $commentData);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'content' => 'This is a test comment on a blog'
                ]);

        $this->assertDatabaseHas('blog_comments', [
            'content' => 'This is a test comment on a blog',
            'user_id' => $this->user->id,
            'blog_id' => $this->blog->id
        ]);
    }

    public function test_comment_creation_requires_content()
    {
        $commentData = [];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/posts/{$this->post->id}/comments", $commentData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['content']);
    }

    public function test_comment_on_nonexistent_post_returns_404()
    {
        $commentData = [
            'content' => 'Comment on non-existent post'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/posts/999999/comments', $commentData);

        $response->assertStatus(404);
    }

    public function test_comment_on_nonexistent_blog_returns_404()
    {
        $commentData = [
            'content' => 'Comment on non-existent blog'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/blogs/999999/comments', $commentData);

        $response->assertStatus(404);
    }

    public function test_user_can_update_own_comment()
    {
        // First create a comment
        $comment = PostComment::create([
            'content' => 'Original comment content',
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);

        $updateData = [
            'content' => 'Updated comment content'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/comments/{$comment->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'content' => 'Updated comment content'
                ]);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Updated comment content'
        ]);
    }

    public function test_user_cannot_update_others_comment()
    {
        // Create a comment by other user
        $comment = PostComment::create([
            'content' => 'Other user comment',
            'user_id' => $this->otherUser->id,
            'post_id' => $this->post->id
        ]);

        $updateData = [
            'content' => 'Trying to update other user comment'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/comments/{$comment->id}", $updateData);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_comment()
    {
        // First create a comment
        $comment = PostComment::create([
            'content' => 'Comment to delete',
            'user_id' => $this->user->id,
            'post_id' => $this->post->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id
        ]);
    }

    public function test_user_cannot_delete_others_comment()
    {
        // Create a comment by other user
        $comment = PostComment::create([
            'content' => 'Other user comment',
            'user_id' => $this->otherUser->id,
            'post_id' => $this->post->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id
        ]);
    }

    public function test_comment_includes_user_relationship()
    {
        $commentData = [
            'content' => 'Comment with user relationship'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/posts/{$this->post->id}/comments", $commentData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'content',
                        'user' => [
                            'id',
                            'name',
                            'handle',
                            'bio',
                            'profile_photo_url'
                        ],
                        'created_at',
                        'updated_at'
                    ]
                ]);
    }

    public function test_comment_validation_enforces_content_length()
    {
        $commentData = [
            'content' => str_repeat('a', 1025) // Exceeds max length of 1024
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/posts/{$this->post->id}/comments", $commentData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['content']);
    }

    public function test_comment_validation_rejects_empty_content()
    {
        $commentData = [
            'content' => ''
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/posts/{$this->post->id}/comments", $commentData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['content']);
    }

    public function test_comment_validation_rejects_whitespace_only_content()
    {
        $commentData = [
            'content' => '   '
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/posts/{$this->post->id}/comments", $commentData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['content']);
    }

    public function test_nonexistent_comment_update_returns_404()
    {
        $updateData = [
            'content' => 'Trying to update non-existent comment'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/comments/999999', $updateData);

        $response->assertStatus(404);
    }

    public function test_nonexistent_comment_delete_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/comments/999999');

        $response->assertStatus(404);
    }

    public function test_blog_comment_update_works_correctly()
    {
        // Create a blog comment
        $comment = BlogComment::create([
            'content' => 'Original blog comment',
            'user_id' => $this->user->id,
            'blog_id' => $this->blog->id
        ]);

        $updateData = [
            'content' => 'Updated blog comment'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/comments/{$comment->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'content' => 'Updated blog comment'
                ]);

        $this->assertDatabaseHas('blog_comments', [
            'id' => $comment->id,
            'content' => 'Updated blog comment'
        ]);
    }

    public function test_blog_comment_delete_works_correctly()
    {
        // Create a blog comment
        $comment = BlogComment::create([
            'content' => 'Blog comment to delete',
            'user_id' => $this->user->id,
            'blog_id' => $this->blog->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('blog_comments', [
            'id' => $comment->id
        ]);
    }
}
