<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'body' => 'required|string',
        ]);

        $post = Post::findOrFail($data['post_id']);

        $post->comments()->create([
            'body' => $data['body'],
            'author_id' => Auth::id(),
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Comment added successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $data = $request->validate([
            'body' => 'required|string',
        ]);

        $comment->update($data);

        return redirect()->back()->with('success', 'Comment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}
