<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function show(Post $post)
    {
        return view('post', [
            'post' => $post,
        ]);
    }

    public function store(Post $post)
    {
        \request()->validate([
            'score' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['required', 'string'],
        ]);

        Comment::create([
            'user_id' => Auth::user()->id,
            'post_id' => $post->id,
            'score' => \request()->score,
            'body' => \request()->body
        ]);

        return redirect("/post/{$post->slug}");
    }

    public function edit($slug, Comment $comment)
    {
        if (Auth::user()->id == $comment->user->id) {
            return view('edit-comment', [
                'comment' => $comment,
                'slug' => $slug
            ]);
        } else
            abort(403);
    }

    public function update($slug, $id)
    {
        \request()->validate([
            'score' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['required', 'string'],
        ]);

        $comment = Comment::find($id);

        $comment->update([
            'score' => \request()->score,
            'body' => \request()->body
        ]);

        return redirect("/post/$slug");
    }

    public function destroy($slug, $id)
    {
        $comment = Comment::find($id);
        if (Auth::user()->id == $comment->user->id) {
            $comment->delete();
            return redirect("/post/$slug");
        } else
            abort(403);
    }
}
