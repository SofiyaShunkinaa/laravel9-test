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
            $posts = Post::with('author')->paginate(10);
        } else {
            $posts = Post::with('author')->where('author_id', $user->id)->paginate(10);
        }
    
        return view('dashboard.posts.index', compact('posts'));
    }

    public function publicIndex(Request $request)
    {
        $sortBy = in_array($request->get('sort_by'), ['published_at', 'title', 'created_at']) ? $request->get('sort_by') : 'published_at';
        $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

        $posts = Post::with(['author:id,name,email', 'latestComment'])
            ->withCount('comments')
            ->orderBy($sortBy, $direction)
            ->paginate(10)
            ->appends($request->only(['sort_by', 'direction']));

        return view('pages.home', compact('posts', 'sortBy', 'direction'));
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
            'status' => 'in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $data['author_id'] = Auth::id();

        Post::create($data);

        return redirect()->route('dashboard.posts.index')->with('success', 'Post created successfully.');
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
            'status' => 'in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        $post->update($data);

        return redirect()->route('dashboard.posts.index')->with('success', 'Post updated successfully.');
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
