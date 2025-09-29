<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'author_id',
        'published_at',
        'status',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted()
    {
        // Clear posts API cache when post is created, updated, or deleted
        static::created(function () {
            static::clearPostsApiCache();
            static::clearStatsCache();
        });

        static::updated(function () {
            static::clearPostsApiCache();
            static::clearStatsCache();
        });

        static::deleted(function () {
            static::clearPostsApiCache();
            static::clearStatsCache();
        });
    }

    private static function clearPostsApiCache()
    {
        // Clear common cache keys for posts API
        $sortOptions = ['created_at', 'title', 'comments_count'];
        $sortDirs = ['asc', 'desc'];
        $perPages = [10, 20, 50];
        
        foreach ($sortOptions as $sortBy) {
            foreach ($sortDirs as $sortDir) {
                foreach ($perPages as $perPage) {
                    // Clear first few pages (most commonly accessed)
                    for ($page = 1; $page <= 5; $page++) {
                        Cache::forget("posts_api_{$sortBy}_{$sortDir}_{$perPage}_{$page}");
                    }
                }
            }
        }
    }

    private static function clearStatsCache()
    {
        // Clear statistics cache
        Cache::forget('stats_posts_all_all');
        Cache::forget('stats_comments_all_all');
        Cache::forget('stats_users');
        
        // Clear common date range caches (last 30 days)
        for ($i = 0; $i < 30; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');
            Cache::forget("stats_posts_{$date}_all");
            Cache::forget("stats_comments_{$date}_all");
            Cache::forget("stats_posts_all_{$date}");
            Cache::forget("stats_comments_all_{$date}");
        }
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function latestComment(){
        return $this->hasOne(Comment::class)->latestOfMany();
    }
}
