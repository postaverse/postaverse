<?php

namespace Tests\Feature\Api;

use App\Models\User\User;
use App\Models\Post\Post;
use App\Models\Blog\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SearchApiTest extends TestCase
{
    use RefreshDatabase;

    private $user;
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

        $this->token = $this->user->createToken('Test Token')->plainTextToken;

        // Create test data for searching
        $this->createTestData();
    }

    private function createTestData()
    {
        // Create users
        User::create([
            'name' => 'John Developer',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'handle' => 'johndeveloper',
            'email_verified_at' => now(),
            'bio' => 'Laravel enthusiast and PHP developer'
        ]);

        User::create([
            'name' => 'Jane Designer',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'handle' => 'janedesigner',
            'email_verified_at' => now(),
            'bio' => 'UI/UX designer who loves clean interfaces'
        ]);

        // Create posts
        Post::create([
            'title' => 'Laravel Best Practices',
            'content' => 'Here are some Laravel development tips and tricks',
            'user_id' => $this->user->id,
        ]);

        Post::create([
            'title' => 'Vue.js Components',
            'content' => 'Building reusable Vue components for web applications',
            'user_id' => $this->user->id,
        ]);

        Post::create([
            'title' => 'Design Principles',
            'content' => 'Understanding modern design principles for better UX',
            'user_id' => $this->user->id,
        ]);

        // Create blogs
        Blog::create([
            'title' => 'Advanced Laravel Techniques',
            'content' => 'Deep dive into Laravel framework advanced features',
            'user_id' => $this->user->id,
        ]);

        Blog::create([
            'title' => 'JavaScript ES6 Features',
            'content' => 'Modern JavaScript features every developer should know',
            'user_id' => $this->user->id,
        ]);

        Blog::create([
            'title' => 'Design System Implementation',
            'content' => 'How to implement a design system in your organization',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_guest_cannot_access_search()
    {
        $response = $this->getJson('/api/search?q=laravel');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_search_all()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search?q=laravel');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'users' => [
                        'data' => [
                            '*' => [
                                'id',
                                'name',
                                'handle',
                                'bio',
                                'profile_photo_url'
                            ]
                        ]
                    ],
                    'posts' => [
                        'data' => [
                            '*' => [
                                'id',
                                'title',
                                'content',
                                'user'
                            ]
                        ]
                    ],
                    'blogs' => [
                        'data' => [
                            '*' => [
                                'id',
                                'title',
                                'content',
                                'user'
                            ]
                        ]
                    ]
                ]);
    }

    public function test_search_requires_query_parameter()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search');

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['q']);
    }

    public function test_search_rejects_empty_query()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search?q=');

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['q']);
    }

    public function test_search_rejects_short_query()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search?q=ab'); // Assuming minimum length is 3

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['q']);
    }

    public function test_user_search_finds_matching_users()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search/users?q=john');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'handle',
                            'bio',
                            'profile_photo_url'
                        ]
                    ]
                ]);

        $users = $response->json('data');
        $this->assertGreaterThan(0, count($users));
        
        // Check if John Developer is in results
        $foundJohn = collect($users)->contains(function ($user) {
            return str_contains(strtolower($user['name']), 'john');
        });
        $this->assertTrue($foundJohn);
    }

    public function test_user_search_by_handle()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search/users?q=johndeveloper');

        $response->assertStatus(200);

        $users = $response->json('data');
        $this->assertGreaterThan(0, count($users));
        
        // Check if user with handle is found
        $foundUser = collect($users)->contains(function ($user) {
            return $user['handle'] === 'johndeveloper';
        });
        $this->assertTrue($foundUser);
    }

    public function test_user_search_by_bio()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search/users?q=Laravel enthusiast');

        $response->assertStatus(200);

        $users = $response->json('data');
        $this->assertGreaterThan(0, count($users));
    }

    public function test_post_search_finds_matching_posts()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search/posts?q=laravel');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'content',
                            'user'
                        ]
                    ]
                ]);

        $posts = $response->json('data');
        $this->assertGreaterThan(0, count($posts));
        
        // Check if Laravel posts are found
        $foundLaravel = collect($posts)->contains(function ($post) {
            return str_contains(strtolower($post['title']), 'laravel') ||
                   str_contains(strtolower($post['content']), 'laravel');
        });
        $this->assertTrue($foundLaravel);
    }

    public function test_post_search_by_content()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search/posts?q=Vue components');

        $response->assertStatus(200);

        $posts = $response->json('data');
        $this->assertGreaterThan(0, count($posts));
    }

    public function test_blog_search_finds_matching_blogs()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search/blogs?q=javascript');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'content',
                            'user'
                        ]
                    ]
                ]);

        $blogs = $response->json('data');
        $this->assertGreaterThan(0, count($blogs));
        
        // Check if JavaScript blogs are found
        $foundJS = collect($blogs)->contains(function ($blog) {
            return str_contains(strtolower($blog['title']), 'javascript') ||
                   str_contains(strtolower($blog['content']), 'javascript');
        });
        $this->assertTrue($foundJS);
    }

    public function test_blog_search_by_content()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search/blogs?q=design system');

        $response->assertStatus(200);

        $blogs = $response->json('data');
        $this->assertGreaterThan(0, count($blogs));
    }

    public function test_search_returns_empty_results_for_no_matches()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search?q=nonexistentterm123');

        $response->assertStatus(200)
                ->assertJson([
                    'users' => ['data' => []],
                    'posts' => ['data' => []],
                    'blogs' => ['data' => []]
                ]);
    }

    public function test_user_search_returns_empty_for_no_matches()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search/users?q=nonexistentuser123');

        $response->assertStatus(200)
                ->assertJson([
                    'data' => []
                ]);
    }

    public function test_post_search_returns_empty_for_no_matches()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search/posts?q=nonexistentpost123');

        $response->assertStatus(200)
                ->assertJson([
                    'data' => []
                ]);
    }

    public function test_blog_search_returns_empty_for_no_matches()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search/blogs?q=nonexistentblog123');

        $response->assertStatus(200)
                ->assertJson([
                    'data' => []
                ]);
    }

    public function test_search_is_case_insensitive()
    {
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search/posts?q=LARAVEL');

        $response2 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search/posts?q=laravel');

        $response1->assertStatus(200);
        $response2->assertStatus(200);

        $posts1 = $response1->json('data');
        $posts2 = $response2->json('data');

        // Should return same results regardless of case
        $this->assertCount(count($posts2), $posts1);
    }

    public function test_search_pagination_works()
    {
        // Create many posts to test pagination
        for ($i = 1; $i <= 25; $i++) {
            Post::create([
                'title' => "Laravel Tutorial Part $i",
                'content' => "Content about Laravel development tutorial part $i",
                'user_id' => $this->user->id,
            ]);
        }

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search/posts?q=Laravel Tutorial&page=1');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data',
                    'links',
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

    public function test_search_with_special_characters()
    {
        // Create content with special characters
        Post::create([
            'title' => 'C++ Programming Tips',
            'content' => 'Tips for C++ programming with special characters: @#$%',
            'user_id' => $this->user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/search/posts?q=C%2B%2B'); // URL encoded C++

        $response->assertStatus(200);

        $posts = $response->json('data');
        $this->assertGreaterThan(0, count($posts));
    }
}
