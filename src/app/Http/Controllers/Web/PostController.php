<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->role->name === 'admin') {
            $posts = Post::with('author')
            ->withCount('comments')
            ->latest()
            ->paginate(10);
        } else {
            $posts = Post::with('author')
            ->withCount('comments')
            ->where('author_id', $user->id)
            ->latest()
            ->paginate(10);
        }
    
        return view('dashboard.posts.index', compact('posts'));
    }

    public function publicIndex(Request $request)
    {
        $query = Post::with(['author:id,name,email', 'latestComment'])
            ->withCount('comments');

        if ($q = $request->get('q')) {
            $query->where(function($qBuilder) use ($q) {
                $qBuilder->where('title', 'ILIKE', "%{$q}%")
                        ->orWhere('body', 'ILIKE', "%{$q}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $from = $request->get('from');
        $to = $request->get('to');

        if ($from) {
            $query->whereDate('published_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('published_at', '<=', $to);
        }

        $sortBy = in_array($request->get('sort_by'), ['published_at', 'title', 'created_at']) 
            ? $request->get('sort_by') 
            : 'published_at';
        $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

        $posts = $query->orderBy($sortBy, $direction)
            ->paginate(10)
            ->appends($request->only(['q','status','from','to','sort_by','direction']));

        return view('pages.home', compact('posts','sortBy','direction','q','status','from','to'));
    }

    public function publicShow(Post $post)
    {
        $post->load(['author', 'comments.author']);
        return view('pages.post_show', compact('post'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Post::class);

        $data = $request->validate([
            'title' => 'required|string|unique:posts,title',
            'body' => 'required|string',
        ]);

        // Определяем статус по кнопке
        $data['status'] = $request->input('action') === 'publish' ? 'published' : 'draft';

        // Дата публикации только если пост публикуется
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        $data['author_id'] = Auth::id();

        Post::create($data);

        return redirect()->route('dashboard.posts.index')->with('success', 'Пост успешно создан.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('dashboard.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $data = $request->validate([
            'title' => 'required|string|unique:posts,title,' . $post->id,
            'body' => 'required|string',
        ]);

        $newStatus = $request->input('action') === 'publish' ? 'published' : 'draft';

        $data['status'] = $newStatus;

        $data['published_at'] = $newStatus === 'published' && !$post->published_at ? now() : $post->published_at;

        $post->update($data);

        return redirect()->route('dashboard.posts.index')->with('success', 'Пост успешно обновлён.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('dashboard.posts.index')->with('success', 'Post deleted successfully.');
    }
}
