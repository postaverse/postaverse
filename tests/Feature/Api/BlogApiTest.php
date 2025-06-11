<?php

namespace Tests\Feature\Api;

use App\Models\User\User;
use App\Models\Blog\Blog;
use App\Models\Blog\BlogLike;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BlogApiTest extends TestCase
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

    public function test_guest_cannot_access_blogs_index()
    {
        $response = $this->getJson('/api/blogs');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_get_blogs_index()
    {
        // Create some blogs
        Blog::create([
            'title' => 'Test Blog 1',
            'content' => 'Content of test blog 1',
            'user_id' => $this->user->id,
        ]);

        Blog::create([
            'title' => 'Test Blog 2',
            'content' => 'Content of test blog 2',
            'user_id' => $this->otherUser->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/blogs');

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

    public function test_user_can_create_blog()
    {
        $blogData = [
            'title' => 'New Test Blog',
            'content' => 'This is the content of the new test blog'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/blogs', $blogData);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'title' => 'New Test Blog',
                    'content' => 'This is the content of the new test blog'
                ]);

        $this->assertDatabaseHas('blogs', [
            'title' => 'New Test Blog',
            'user_id' => $this->user->id
        ]);
    }

    public function test_blog_creation_requires_title()
    {
        $blogData = [
            'content' => 'This is content without a title'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/blogs', $blogData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['title']);
    }

    public function test_blog_creation_requires_content()
    {
        $blogData = [
            'title' => 'Title without content'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/blogs', $blogData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['content']);
    }

    public function test_user_can_view_specific_blog()
    {
        $blog = Blog::create([
            'title' => 'Specific Test Blog',
            'content' => 'Content of specific test blog',
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/blogs/{$blog->id}");

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'id' => $blog->id,
                    'title' => 'Specific Test Blog',
                    'content' => 'Content of specific test blog'
                ]);
    }

    public function test_user_can_update_own_blog()
    {
        $blog = Blog::create([
            'title' => 'Original Blog Title',
            'content' => 'Original blog content',
            'user_id' => $this->user->id,
        ]);

        $updateData = [
            'title' => 'Updated Blog Title',
            'content' => 'Updated blog content'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/blogs/{$blog->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'title' => 'Updated Blog Title',
                    'content' => 'Updated blog content'
                ]);

        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'title' => 'Updated Blog Title',
            'content' => 'Updated blog content'
        ]);
    }

    public function test_user_cannot_update_others_blog()
    {
        $blog = Blog::create([
            'title' => 'Other User Blog',
            'content' => 'Content by other user',
            'user_id' => $this->otherUser->id,
        ]);

        $updateData = [
            'title' => 'Trying to update',
            'content' => 'Trying to update content'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/blogs/{$blog->id}", $updateData);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_blog()
    {
        $blog = Blog::create([
            'title' => 'Blog to delete',
            'content' => 'This blog will be deleted',
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/blogs/{$blog->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('blogs', [
            'id' => $blog->id
        ]);
    }

    public function test_user_cannot_delete_others_blog()
    {
        $blog = Blog::create([
            'title' => 'Other User Blog',
            'content' => 'Content by other user',
            'user_id' => $this->otherUser->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/blogs/{$blog->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id
        ]);
    }

    public function test_user_can_like_blog()
    {
        $blog = Blog::create([
            'title' => 'Blog to like',
            'content' => 'This blog will be liked',
            'user_id' => $this->otherUser->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/blogs/{$blog->id}/like");

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'liked' => true
                ]);

        $this->assertDatabaseHas('blog_likes', [
            'user_id' => $this->user->id,
            'blog_id' => $blog->id
        ]);
    }

    public function test_user_can_unlike_blog()
    {
        $blog = Blog::create([
            'title' => 'Blog to unlike',
            'content' => 'This blog will be unliked',
            'user_id' => $this->otherUser->id,
        ]);

        // First like the blog
        BlogLike::create([
            'user_id' => $this->user->id,
            'blog_id' => $blog->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("/api/blogs/{$blog->id}/like");

        $response->assertStatus(200)
                ->assertJsonFragment([
                    'liked' => false
                ]);

        $this->assertDatabaseMissing('blog_likes', [
            'user_id' => $this->user->id,
            'blog_id' => $blog->id
        ]);
    }

    public function test_nonexistent_blog_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/blogs/999999');

        $response->assertStatus(404);
    }

    public function test_blogs_are_ordered_by_latest_first()
    {
        $firstBlog = Blog::create([
            'title' => 'First Blog',
            'content' => 'Created first',
            'user_id' => $this->user->id,
        ]);
        
        // Ensure a timestamp difference
        sleep(1);

        $secondBlog = Blog::create([
            'title' => 'Second Blog',
            'content' => 'Created second',
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/blogs');

        $response->assertStatus(200);
        
        $blogs = $response->json('data');
        // Check that the most recent blog comes first
        $this->assertEquals($secondBlog->title, $blogs[0]['title']);
        $this->assertEquals($firstBlog->title, $blogs[1]['title']);
    }

    public function test_blog_validation_enforces_title_length()
    {
        $blogData = [
            'title' => str_repeat('a', 256), // Assuming max length is 255
            'content' => 'Valid content'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/blogs', $blogData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['title']);
    }

    public function test_blog_validation_enforces_content_length()
    {
        $blogData = [
            'title' => 'Valid title',
            'content' => str_repeat('a', 10001) // Assuming max length is 10000
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/blogs', $blogData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['content']);
    }

    public function test_blog_includes_user_relationship()
    {
        $blog = Blog::create([
            'title' => 'Blog with User',
            'content' => 'Content with user relationship',
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/blogs/{$blog->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'title',
                        'content',
                        'user' => [
                            'id',
                            'name',
                            'handle',
                            'bio',
                            'profile_photo_url'
                        ]
                    ]
                ]);
    }
}
