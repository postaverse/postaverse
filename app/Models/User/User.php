<?php

namespace App\Models\User;

use App\Models\Interaction\Follower;
use App\Models\Interaction\Notification;
use App\Models\Post\Comment;
use App\Models\Post\Like;
use App\Models\Post\Post;
use App\Models\Blog\Blog;
use App\Models\Blog\BlogComment;
use App\Models\Blog\BlogLike;
use App\Models\Admin\AdminLogs;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JoelButcher\Socialstream\HasConnectedAccounts;
use JoelButcher\Socialstream\SetsProfilePhotoFromUrl;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Http;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasConnectedAccounts;
    use HasFactory;
    use HasProfilePhoto {
        HasProfilePhoto::profilePhotoUrl as getPhotoUrl;
    }
    use Notifiable;
    use SetsProfilePhotoFromUrl;
    use TwoFactorAuthenticatable;

    /**
     * Override the email verification notification sending.
     * We're using a custom verification system with PendingUser model, so
     * we don't need to send the standard Laravel verification email.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        // Don't send the standard Laravel verification email
        // Our email is sent during PendingUser creation
        return;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'handle',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Get the URL to the user's profile photo.
     */
    public function profilePhotoUrl(): Attribute
    {
        if ($this->profile_photo_path === null) {
            return Attribute::get(fn () => 'https://gravatar.com/avatar/'.md5(strtolower($this->email)).'?s=200&d=mp&d=retro');
        }
        return filter_var($this->profile_photo_path, FILTER_VALIDATE_URL)
            ? Attribute::get(fn () => $this->profile_photo_path)
            : $this->getPhotoUrl();
    }

    /**
     * Get the user's bio.
     */
    public function bio(): string
    {
        if ($this->attributes['bio'] === null || $this->attributes['bio'] === '') {
            $response = Http::get('https://gravatar.com/'.md5(strtolower($this->email)).'.json');
            if ($response->successful()) {
                $data = $response->json();
                $aboutMe = $data['entry'][0]['aboutMe'] ?? '';
                return $aboutMe;
            }
            return '';
        }
        return $this->attributes['bio'];
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function blockedUsers()
    {
        return $this->hasMany(BlockedUser::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->whereNull('read_at');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }

    public function follows()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }
    
    public function follow($user)
    {
        // Make sure we're not following ourselves and not already following this user
        if ($user->id != $this->id && !$this->follows()->where('following_id', $user->id)->exists()) {
            return $this->follows()->attach($user->id);
        }
    }
    
    public function unfollow($user)
    {
        // Make sure we're not trying to unfollow ourselves
        if ($user->id != $this->id) {
            return $this->follows()->detach($user->id);
        }
    }

    public function isSiteVerified()
    {
        return $this->sites->contains('is_verified', true);
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    public function blogLikes(): HasMany
    {
        return $this->hasMany(BlogLike::class);
    }

    public function blogComments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
    }

    public function bans()
    {
        return $this->hasMany(Banned::class);
    }

    public function adminLogs()
    {
        return $this->hasMany(AdminLogs::class);
    }
}