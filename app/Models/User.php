<?php

namespace App\Models;

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

class User extends Authenticatable
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
            'meteors_last_redeemed_at' => 'datetime',
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

    public function badges()
    {
        return $this->belongsToMany(Badge::class);
    }

    public function meteors()
    {
        return $this->hasMany(Meteor::class);
    }

    public function meteorQuantity()
    {
        // Must return relationship instance
        return $this->hasOne(Meteor::class)->select('quantity')->withDefault(['quantity' => 0]);
    }

    public function addMeteors(int $quantity)
    {
        $meteor = $this->meteors()->first(); // Attempt to retrieve the user's existing Meteor record

        if ($meteor) {
            // If a Meteor record exists, update the quantity
            $meteor->quantity += $quantity;
            $meteor->save();
        } else {
            // If no Meteor record exists, create a new one with the specified quantity
            $this->meteors()->create([
                'quantity' => $quantity,
            ]);
        }
    }

    public function textThemes()
    {
        return $this->belongsToMany(TextTheme::class, 'text_theme_user')->withPivot('equipped');
    }

    public function hasTextTheme($themeId)
    {
        return $this->textThemes->contains($themeId);
    }

    public function isSiteVerified()
    {
        return $this->sites->contains('is_verified', true);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
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