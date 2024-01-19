<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function show($slug)
    {
        $user = Auth::user();

        if ($user && ($user->isAdmin())) {
            $dbPost = Post::with([
                'user' => function ($query) {
                    $query->withTrashed();
                }
            ]);

            $dbPost->withTrashed();

            $dbComment = Comment::with([
                'user' => function ($query) {
                    $query->withTrashed();
                }
            ]);

            $dbComment->withTrashed();
        } else {
            $dbPost = Post::with(['user']);
            $dbPost->whereDoesntHave('user', function ($query) {
                $query->where('banned_until', '>', now());
            });

            $dbComment = Comment::with(['user']);
            $dbComment->whereDoesntHave('user', function ($query) {
                $query->where('banned_until', '>', now());
            });
        }

        $post = $dbPost->where('slug', $slug)->firstOrFail();
        $comments = $dbComment->orderByDesc('created_at')->get();

        return view('post', [
            'post' => $post,
            'comments' => $comments,
        ]);
    }

    public function store(Post $post)
    {
        \request()->validate([
            'score' => ['required', 'integer', 'min:1', 'max:5'],
            'body' => ['required', 'string'],
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'score' => \request()->score,
            'body' => \request()->body
        ]);

        return redirect("/post/{$post->slug}");
    }

    public function edit($slug, Comment $comment)
    {
        if (Auth::id() == $comment->user->id) {
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
        if (Auth::id() == $comment->user->id) {
            $comment->delete();
            return redirect("/post/$slug");
        } else
            abort(403);
    }
}
