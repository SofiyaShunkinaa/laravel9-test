<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    // -----------------------------
    // Статистика по постам
    // -----------------------------
    public function posts(Request $request)
    {
        $from = $request->query('from') ? Carbon::parse($request->query('from')) : null;
        $to = $request->query('to') ? Carbon::parse($request->query('to')) : null;

        // Total by status
        $queryStatus = Post::query();
        if ($from) $queryStatus->where('created_at', '>=', $from);
        if ($to) $queryStatus->where('created_at', '<=', $to);
        $totalByStatus = $queryStatus->select('status', DB::raw('count(*) as total'))
                                     ->groupBy('status')
                                     ->pluck('total', 'status');

        // Total by period
        $queryPeriod = Post::query();
        if ($from) $queryPeriod->where('created_at', '>=', $from);
        if ($to) $queryPeriod->where('created_at', '<=', $to);
        $totalByPeriod = $queryPeriod->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                                     ->groupBy('date')
                                     ->orderBy('date')
                                     ->get();

        // Average comments
        $queryAvg = Post::query();
        if ($from) $queryAvg->where('created_at', '>=', $from);
        if ($to) $queryAvg->where('created_at', '<=', $to);
        $avgComments = $queryAvg->withCount('comments')->get()->avg('comments_count');

        // Top 5 most commented
        $queryTop = Post::query();
        if ($from) $queryTop->where('created_at', '>=', $from);
        if ($to) $queryTop->where('created_at', '<=', $to);
        $top5Posts = $queryTop->withCount('comments')
                               ->orderBy('comments_count', 'desc')
                               ->take(5)
                               ->get(['id', 'title', 'comments_count']);

        return response()->json([
            'total_by_status' => $totalByStatus,
            'total_by_period' => $totalByPeriod,
            'avg_comments' => $avgComments,
            'top5_most_commented' => $top5Posts,
        ]);
    }

    // -----------------------------
    // Статистика по комментариям
    // -----------------------------
    public function comments(Request $request)
    {
        $from = $request->query('from') ? Carbon::parse($request->query('from')) : null;
        $to = $request->query('to') ? Carbon::parse($request->query('to')) : null;

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
    // Статистика по пользователям
    // -----------------------------
    public function users(Request $request)
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
