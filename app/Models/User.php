<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password',
        'github_id', 'github_token', 'github_refresh_token',
        'google_id', 'facebook_id', 'facebook_token', 'profile_photo_path',
        'social_user', 'social_email',
    ];

    /**
     * The attributes that should be hidden for serialization.
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'is_social',
        'social_token',
    ];

    public function isSocial(): Attribute
    {
        return Attribute::get(function() {
            return $this->social_user;
        });
    }

    public function socialToken(): Attribute
    {
        return Attribute::get(function() {
            $token = null;
            if ($this->facebook_token){
                $token = $this->facebook_token;
            } else if ($this->github_token){
                $token = $this->github_token;
            } else if ($this->google_token){
                $token = $this->google_token;
            }
            return $token;
        });
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function profilePhotoUrl(): Attribute
    {
        // if ($this->isSocialUser()) {
        //     $profile_photo_path = $this->profile_photo_path;
            // if ($this->facebook_id) {
                // $this->profile_photo_path .= "&access_token=" . $this->facebook_token;
            // }
        //     return Attribute::get(function() {
        //         return $this->profile_photo_path
        //                 ? Storage::disk($this->profilePhotoDisk())->url($this->profile_photo_path)
        //                 : $this->defaultProfilePhotoUrl();
        //     });
        // } else {

        if ($this->isRegisteredUser() || $this->profile_photo_path == null) {
            // we use the default icon and store any uploads
            return Attribute::get(function() {
                return $this->defaultProfilePhotoUrl();
            });
        }

        if ($this->isSocialUser() && $this->facebook_id) {
            // we download the facebook avatar and store it
            return Attribute::get(function() {
                if (str_contains($this->profile_photo_path, "/profile-photos")) {
                    return $this->profile_photo_path;
                }
                return "{$this->profile_photo_path}&access_token={$this->facebook_token}";
            });
        }

        if ($this->isSocialUser()) {
            // we just use the avatar url given by the provider
            return Attribute::get(function() {
                return $this->profile_photo_path;
            });
        }

        // return Attribute::get(function() {
        //     return $this->profile_photo_path
        //             ? Storage::disk($this->profilePhotoDisk())->url($this->profile_photo_path)
        //             : $this->defaultProfilePhotoUrl();
        // });
        // }

        // }
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
            $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
                return mb_substr($segment, 0, 1);
            })->join(' '));

            return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }

    public function isSocialUser() {
        return $this->social_user;
    }

    public function isRegisteredUser() {
        return !$this->isSocialUser();
    }

    /**
     * Update the user's profile photo.
     *
     * @param  $photo
     * @param  string  $storagePath
     * @return void
     */
    public function updateProfilePhoto($photo, $storagePath = 'profile-photos')
    {
        tap($this->profile_photo_path, function ($previous) use ($photo, $storagePath) {
            $this->forceFill([
                'profile_photo_path' => '/storage/' . $photo->storePublicly(
                    $storagePath, ['disk' => $this->profilePhotoDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }

    /**
     * Get the shopping lists associated with this user.
     */
    public function shoppingLists(): HasMany
    {
        return $this->hasMany(ShoppingList::class);
    }
}
