<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PublicPostController extends Controller
{
    public function index(Request $request)
    {
        // Check if we have search/filter parameters that should not be cached
        $hasFilters = $request->filled('q') || $request->filled('from') || $request->filled('to');
        
        if (!$hasFilters) {
            // Cache key for basic queries (no search/filters)
            $sortBy = $request->query('sort_by', 'created_at');
            $sortDir = $request->query('sort_dir', 'desc');
            $perPage = $request->query('per_page', 10);
            $page = $request->query('page', 1);
            
            $cacheKey = "posts_api_{$sortBy}_{$sortDir}_{$perPage}_{$page}";
            
            return Cache::remember($cacheKey, 300, function () use ($request) { // 5 minutes cache
                return $this->getPosts($request);
            });
        }
        
        // For filtered/searched queries, don't cache
        return $this->getPosts($request);
    }
    
    private function getPosts(Request $request)
    {
        $query = Post::query()->withCount('comments')->with(['latestComment', 'author']);

        // --- Date filters ---
        if ($request->filled('from')) {
            $query->where('created_at', '>=', $request->query('from'));
        }
        if ($request->filled('to')) {
            $query->where('created_at', '<=', $request->query('to'));
        }

        // --- Search by title ---
        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where('title', 'ILIKE', "%$q%");
        }

        // --- Sorting ---
        $sortBy = $request->query('sort_by', 'created_at');
        $sortDir = $request->query('sort_dir', 'desc');

        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        $allowedSort = ['created_at', 'title', 'comments_count'];
        if (!in_array($sortBy, $allowedSort)) $sortBy = 'created_at';

        $query->orderBy($sortBy, $sortDir);

        // --- Pagination ---
        $perPage = $request->query('per_page', 10);

        $posts = $query->paginate($perPage)->appends($request->query());

        return response()->json($posts);
    }
}
