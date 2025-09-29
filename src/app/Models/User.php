<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable implements MustVerifyEmail
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
        'role_id',
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
    ];

    protected static function booted()
    {
        // Clear users statistics cache when user is created, updated, or deleted
        static::created(function () {
            Cache::forget('stats_users');
        });

        static::updated(function () {
            Cache::forget('stats_users');
        });

        static::deleted(function () {
            Cache::forget('stats_users');
        });
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($role)
    {
        return $this->role && $this->role->name === $role;
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isEditor()
    {
        return $this->hasRole('editor');
    }

    public function isViewer()
    {
        return $this->hasRole('viewer');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id'); 
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'author_id'); 
    }
}
