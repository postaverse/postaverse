<?php

namespace Tests\Feature\Post;

use App\Models\Post\Post;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    
    protected $user;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a verified user for testing
        $this->user = User::create([
            'name' => 'Test User',
            'handle' => 'testuser',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
    }

    public function test_only_authenticated_users_can_view_feed(): void
    {
        // Guest user should be redirected to login
        $response = $this->get('/feed');
        $response->assertRedirect('/login');
        
        // Set email_verified_at to ensure the user is verified
        $this->user->forceFill(['email_verified_at' => now()])->save();
        
        // Authenticated user can see feed
        $response = $this->actingAs($this->user)
            ->get('/feed');
            
        // The route should either be successful or redirect to a non-login page
        $this->assertTrue($response->status() == 200 || 
                          ($response->status() == 302 && !str_contains($response->getTargetUrl(), '/login')));
    }

    public function test_posts_appear_in_feed(): void
    {
        // Create a few posts for the user
        $post1 = new Post([
            'title' => 'Test Post 1',
            'content' => 'This is test post content 1',
        ]);
        $post1->user()->associate($this->user);
        $post1->save();
        
        $post2 = new Post([
            'title' => 'Test Post 2',
            'content' => 'This is test post content 2',
        ]);
        $post2->user()->associate($this->user);
        $post2->save();
        
        // Set email_verified_at to ensure the user is verified
        $this->user->forceFill(['email_verified_at' => now()])->save();
        $this->user->markEmailAsVerified();
        
        // Check if posts are visible in feed
        $response = $this->actingAs($this->user)
            ->get('/feed');
        
        $response->assertSee('Test Post 1');
        $response->assertSee('Test Post 2');
    }

    public function test_post_detail_page_shows_post_content(): void
    {
        // Create a post
        $post = new Post([
            'title' => 'Detailed Post',
            'content' => 'This is detailed post content with specific text',
        ]);
        $post->user()->associate($this->user);
        $post->save();
        
        // Visit the post detail page
        $response = $this->actingAs($this->user)
            ->get('/post/' . $post->id);
        
        $response->assertSuccessful();
        $response->assertSee('Detailed Post');
        $response->assertSee('This is detailed post content with specific text');
    }

    public function test_users_can_only_delete_their_own_posts(): void
    {
        // Create a post for the authenticated user
        $ownPost = new Post([
            'title' => 'Own Post',
            'content' => 'This is my own post',
        ]);
        $ownPost->user()->associate($this->user);
        $ownPost->save();
        
        // Create another user and post
        $anotherUser = User::create([
            'name' => 'Another User',
            'handle' => 'anotheruser',
            'email' => 'another@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        
        $otherPost = new Post([
            'title' => 'Other Post',
            'content' => 'This is someone else\'s post',
        ]);
        $otherPost->user()->associate($anotherUser);
        $otherPost->save();
        
        // Ensure the user is verified
        $this->user->markEmailAsVerified();
        
        // User should be able to delete their own post
        $response = $this->actingAs($this->user)
            ->withoutMiddleware()
            ->delete('/post/' . $ownPost->id);
            
        $this->assertDatabaseMissing('posts', [
            'id' => $ownPost->id,
        ]);
        
        // User should not be able to delete someone else's post
        $response = $this->actingAs($this->user)
            ->withoutMiddleware()
            ->delete('/post/' . $otherPost->id);
            
        $this->assertDatabaseHas('posts', [
            'id' => $otherPost->id,
        ]);
    }
}
