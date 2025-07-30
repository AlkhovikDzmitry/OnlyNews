<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
public function store(Request $request, Post $post)
{
    $request->validate([
        'content' => 'required|string|max:1000'
    ]);

    $comment = $post->comments()->create([
        'content' => $request->content,
        'user_id' => auth()->id()
    ]);

    $comment->load('user'); 

    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'comment' => [
                'content' => $comment->content,
                'user' => [
                    'name' => $comment->user->name,
                    'avatar_url' => $comment->user->avatar_url
                ]
            ],
            'comments_count' => $post->comments()->count()
        ]);
    }

    return back()->with('success', 'Комментарий добавлен');
}
}