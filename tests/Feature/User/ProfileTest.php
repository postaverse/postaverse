<?php

namespace Tests\Feature\User;

use App\Models\User\User;
use App\Models\Post\Post;
use App\Models\Interaction\Follower;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    
    protected $user;
    protected $anotherUser;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create two verified users for testing
        $this->user = User::create([
            'name' => 'Test User',
            'handle' => 'testuser',
            'email' => 'test@example.com',
            'bio' => 'This is a test bio',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
        
        $this->anotherUser = User::create([
            'name' => 'Another User',
            'handle' => 'anotheruser',
            'email' => 'another@example.com',
            'bio' => 'This is another test bio',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);
    }

    public function test_profile_page_shows_user_information(): void
    {
        // Create a few posts for the user
        $post = new Post([
            'title' => 'Test Profile Post',
            'content' => 'This is a test post for the profile',
        ]);
        $post->user()->associate($this->user);
        $post->save();
        
        $response = $this->get('/@' . $this->user->handle);
        
        $response->assertSuccessful();
        $response->assertSee($this->user->handle);
        $response->assertSee($this->user->bio);
        $response->assertSee('Test Profile Post');
    }
    
    public function test_users_can_follow_other_users(): void
    {
        // Login as the user and mark as verified
        $this->actingAs($this->user);
        $this->user->markEmailAsVerified();
        
        // Follow another user
        $response = $this->post('/follow/' . $this->anotherUser->id);
        
        // Check that the follow was recorded in the database
        $this->assertDatabaseHas('followers', [
            'follower_id' => $this->user->id,
            'following_id' => $this->anotherUser->id,
        ]);
    }
    
    public function test_users_can_unfollow_other_users(): void
    {
        // Create a follow relationship
        Follower::create([
            'follower_id' => $this->user->id,
            'following_id' => $this->anotherUser->id,
        ]);
        
        // Login as the user and mark as verified
        $this->actingAs($this->user);
        $this->user->markEmailAsVerified();
        
        // Unfollow the other user
        $response = $this->delete('/unfollow/' . $this->anotherUser->id);
        
        // Check that there's a redirect
        $response->assertStatus(302);
        
        // Check that the follow was removed from the database
        $this->assertDatabaseMissing('followers', [
            'follower_id' => $this->user->id,
            'following_id' => $this->anotherUser->id,
        ]);
    }
    
    public function test_profile_shows_correct_follower_count(): void
    {
        // Create several followers for the user
        for ($i = 1; $i <= 3; $i++) {
            $follower = User::create([
                'name' => "Follower $i",
                'handle' => "follower$i",
                'email' => "follower$i@example.com",
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);
            
            Follower::create([
                'follower_id' => $follower->id,
                'following_id' => $this->user->id,
            ]);
        }
        
        // Make the user follow someone as well
        Follower::create([
            'follower_id' => $this->user->id,
            'following_id' => $this->anotherUser->id,
        ]);
        
        $response = $this->get('/@' . $this->user->handle);
        
        $response->assertSee('3'); // Followers count
        $response->assertSee('1'); // Following count
    }
}
