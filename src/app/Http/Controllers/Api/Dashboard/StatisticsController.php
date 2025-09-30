<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class StatisticsController extends Controller
{
    // -----------------------------
    // Posts statistics
    // -----------------------------
    public function posts(Request $request)
    {
        $from = $request->query('from') ? Carbon::parse($request->query('from')) : null;
        $to = $request->query('to') ? Carbon::parse($request->query('to')) : null;

        $period = $request->query('period', 'day');

        $cacheKey = 'stats_posts_' . $period . '_' . ($from ? $from->format('Y-m-d') : 'all') . '_' . ($to ? $to->format('Y-m-d') : 'all');

        return Cache::remember($cacheKey, 600, function () use ($from, $to, $period) {
            return $this->getPostsStats($from, $to, $period);
        });        
    }

    private function getPostsStats($from, $to, $period = 'day')
    {
        $queryStatus = Post::query();
        if ($from) $queryStatus->where('created_at', '>=', $from);
        if ($to) $queryStatus->where('created_at', '<=', $to);
        $totalByStatus = $queryStatus->select('status', DB::raw('count(*) as total'))
                                     ->groupBy('status')
                                     ->pluck('total', 'status');

        $query = Post::query();
        if ($from) $query->where('created_at', '>=', $from);
        if ($to) $query->where('created_at', '<=', $to);
        $periods = [];

        if ($period === 'day') {
            for ($i = 9; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $periods[$date] = 0;
            }

            $totals = (clone $query)
                ->selectRaw("DATE(created_at) as date, count(*) as total")
                ->where('created_at', '>=', now()->subDays(9)->startOfDay())
                ->groupByRaw("DATE(created_at)")
                ->pluck('total', 'date');

            foreach ($totals as $date => $count) {
                $periods[$date] = $count;
            }

        } elseif ($period === 'week') {
            for ($i = 9; $i >= 0; $i--) {
                $week = now()->subWeeks($i)->format('o-\WW'); 
                $periods[$week] = 0;
            }

            $totals = (clone $query)
                ->selectRaw("TO_CHAR(created_at, 'IYYY-\"W\"IW') as week, count(*) as total")
                ->where('created_at', '>=', now()->subWeeks(9)->startOfWeek())
                ->groupByRaw("TO_CHAR(created_at, 'IYYY-\"W\"IW')")
                ->pluck('total', 'week');

            foreach ($totals as $week => $count) {
                $periods[$week] = $count;
            }

        } elseif ($period === 'month') {
            for ($i = 9; $i >= 0; $i--) {
                $month = now()->subMonths($i)->format('Y-m');
                $periods[$month] = 0;
            }

            $totals = (clone $query)
                ->selectRaw("TO_CHAR(created_at, 'YYYY-MM') as month, count(*) as total")
                ->where('created_at', '>=', now()->subMonths(9)->startOfMonth())
                ->groupByRaw("TO_CHAR(created_at, 'YYYY-MM')")
                ->pluck('total', 'month');

            foreach ($totals as $month => $count) {
                $periods[$month] = $count;
            }
        }

        $queryAvg = Post::query();
        if ($from) $queryAvg->where('created_at', '>=', $from);
        if ($to) $queryAvg->where('created_at', '<=', $to);
        $avgComments = $queryAvg->withCount('comments')->get()->avg('comments_count');

        $queryTop = Post::query();
        if ($from) $queryTop->where('created_at', '>=', $from);
        if ($to) $queryTop->where('created_at', '<=', $to);
        $top5Posts = $queryTop->withCount('comments')
                               ->orderBy('comments_count', 'desc')
                               ->take(5)
                               ->get(['id', 'title', 'comments_count']);

        return response()->json([
            'total_by_status' => $totalByStatus,
            'posts_by_period' => collect($periods)->map(fn($v, $label) => ['label' => $label, 'total' => $v])->values(),
            'avg_comments' => $avgComments,
            'top5_most_commented' => $top5Posts,
        ]);
    }

    // -----------------------------
    // Comments statistics
    // -----------------------------
    public function comments(Request $request)
    {
        $from = $request->query('from') ? Carbon::parse($request->query('from')) : null;
        $to = $request->query('to') ? Carbon::parse($request->query('to')) : null;

        // Create cache key based on date range
        $cacheKey = 'stats_comments_' . ($from ? $from->format('Y-m-d') : 'all') . '_' . ($to ? $to->format('Y-m-d') : 'all');
        
        return Cache::remember($cacheKey, 600, function () use ($from, $to) { // 10 minutes cache
            return $this->getCommentsStats($from, $to);
        });
    }

    private function getCommentsStats($from, $to)
    {
        // Total comments
        $queryTotal = Comment::query();
        if ($from) $queryTotal->where('created_at', '>=', $from);
        if ($to) $queryTotal->where('created_at', '<=', $to);
        $total = $queryTotal->count();

        // Total by period
        $queryPeriod = Comment::query();
        if ($from) $queryPeriod->where('created_at', '>=', $from);
        if ($to) $queryPeriod->where('created_at', '<=', $to);
        $totalByPeriod = $queryPeriod->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                                     ->groupBy('date')
                                     ->orderBy('date')
                                     ->get();

        // Activity by hour
        $queryHour = Comment::query();
        if ($from) $queryHour->where('created_at', '>=', $from);
        if ($to) $queryHour->where('created_at', '<=', $to);
        $activityByHour = $queryHour->select(DB::raw('EXTRACT(HOUR FROM created_at) as hour'), DB::raw('count(*) as total'))
                                    ->groupBy('hour')
                                    ->orderBy('hour')
                                    ->get();

        // Activity by weekday
        $queryWeekday = Comment::query();
        if ($from) $queryWeekday->where('created_at', '>=', $from);
        if ($to) $queryWeekday->where('created_at', '<=', $to);
        $activityByWeekday = $queryWeekday->select(DB::raw('EXTRACT(DOW FROM created_at) as weekday'), DB::raw('count(*) as total'))
                                          ->groupBy('weekday')
                                          ->orderBy('weekday')
                                          ->get();

        return response()->json([
            'total_comments' => $total,
            'total_by_period' => $totalByPeriod,
            'activity_by_hour' => $activityByHour,
            'activity_by_weekday' => $activityByWeekday,
        ]);
    }

    // -----------------------------
    // Users statistics
    // -----------------------------
    public function users(Request $request)
    {
        // Users stats don't change as frequently, cache for longer
        $cacheKey = 'stats_users';
        
        return Cache::remember($cacheKey, 1800, function () { // 30 minutes cache
            return $this->getUsersStats();
        });
    }

    private function getUsersStats()
    {
        $users = User::with('role')->withCount('posts')->withCount('comments')->get();

        $countByRoles = $users->groupBy(function($user) {
            return optional($user->role)->name ?? 'No Role';
        })->map(function($group) {
            return $group->count();
        });

        $top5Authors = $users->sortByDesc('posts_count')->take(5)->values();

        $top5Commenters = $users->sortByDesc('comments_count')->take(5)->values();

        return response()->json([
            'count_by_roles' => $countByRoles,
            'top5_authors' => $top5Authors,
            'top5_commenters' => $top5Commenters,
        ]);
    }

}
