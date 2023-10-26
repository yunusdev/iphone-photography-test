<?php

namespace App\Models;

use App\Events\BadgeUnlocked;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    # Adding Beginner Badge to User on created
    public static function boot()
    {
        parent::boot();
        static::created(function ($user): void {
            event(new BadgeUnlocked('Beginner', $user));
        });
    }

    /**
     * The comments that belong to the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }

    /**
     * The count of lessons that a user has watched.
     */
    public function watchedCount(): int
    {
        return $this->watched()->count();
    }

    /**
     * The comments count that belong to the user.
     */
    public function commentsCount(): int
    {
        return $this->comments()->count();
    }

    /**
     * User achievements
     */
    public function achievements(): MorphToMany
    {
        return $this->morphedByMany(Achievement::class, 'scorable')->withTimestamps();
    }

    /**
     * User achievements count
     */
    public function achievementsCount(): int
    {
        return $this->achievements()->count();
    }

    /**
     * User badges
     */
    public function badges(): MorphToMany
    {
        return $this->morphedByMany(Badge::class, 'scorable')->withTimestamps();
    }

    /**
     * Current User badge
     */
    public function currentBadge()
    {
        return $this->badges()->orderBy('number', 'desc')->first();
    }
}

