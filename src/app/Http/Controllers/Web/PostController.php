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

    public function publicIndex()
    {
        return view('pages.home');
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

        try {
            $post = Post::create($data);
            return redirect()->route('dashboard.posts.index')->with('success', 'Пост успешно создан.');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'message' => 'A post with this title already exists.'
                ], 409); 
            }

            throw $e;
        }
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
