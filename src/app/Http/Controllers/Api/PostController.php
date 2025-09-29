<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = Post::with('author');

        if ($user->cannot('viewAny', Post::class)) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($user->role->name !== 'admin') {
            $query->where('author_id', $user->id);
        }

        return response()->json(
            $query->paginate(10)
        );
    }

    public function store(Request $request)
    {
        $this->authorize('create', Post::class);

        $data = $request->validate([
            'title' => 'required|string|unique:posts,title',
            'body' => 'required|string',
            'status' => 'in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $data['author_id'] = Auth::id();

        $post = Post::create($data);

        return response()->json($post, 201);
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);

        return response()->json($post->load(['author:id,name,email', 'comments.author']));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $request->validate([
            'title' => 'required|string|unique:posts,title,' . $post->id,
            'body' => 'required|string',
            'status' => 'in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $post->update($data);

        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully.']);
    }
}
