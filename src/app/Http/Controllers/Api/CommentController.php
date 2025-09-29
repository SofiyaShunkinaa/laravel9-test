<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $this->authorize('view', $post); // можно создавать коммент, если можешь видеть пост

        $data = $request->validate([
            'body' => 'required|string',
        ]);

        $comment = $post->comments()->create([
            'body' => $data['body'],
            'author_id' => Auth::id(),
        ]);

        return response()->json($comment, 201);
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $data = $request->validate([
            'body' => 'required|string',
        ]);

        $comment->update($data);

        return response()->json($comment);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully.']);
    }
}
