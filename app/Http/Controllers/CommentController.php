<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentController extends Controller
{
    public function show($slug)
    {
        $loggedUser = Auth::user();

        if ($loggedUser && ($loggedUser->isAdmin())) {
            $dbPost = Post::with([
                'user' => function (BelongsTo $query) {
                    $query->withTrashed();
                }
            ]);

            $dbPost->withTrashed();

            $dbComment = Comment::with([
                'user' => function (BelongsTo $query) {
                    $query->withTrashed();
                }
            ]);

            $dbComment->withTrashed();
        } else {
            $dbPost = Post::with(['user']);
            $dbPost->whereDoesntHave('user', function (Builder $query) {
                $query->where('banned_until', '>', now());
            });

            $dbComment = Comment::with(['user']);
            $dbComment->whereDoesntHave('user', function (Builder $query) {
                $query->where('banned_until', '>', now());
            });
        }

        $post = $dbPost->where('slug', $slug)->firstOrFail();

        if ($post->is_published == 0) {
            if (!$loggedUser || ((!$loggedUser->isAdmin() && $loggedUser->id != $post->user_id))) {
                abort(404);
            }
        }

        $comments = $dbComment->orderByDesc('created_at')->get();
        $userHasComment = false;

        if ($loggedUser) {
            $userHasComment = Comment::with([])
                ->where('user_id', $loggedUser->id)
                ->where('post_id', $post->id)
                ->exists();
        }

        return view('post', [
            'post' => $post,
            'comments' => $comments,
            'userHasComment' => $userHasComment,
        ]);
    }

    public function store(Post $post)
    {
        \request()->validate([
            'score' => ['nullable', 'integer', 'min:1', 'max:5'],
            'body' => ['required', 'string'],
        ]);

        $userId = Auth::id();

        if (Comment::with([])
                ->where('user_id', $userId)
                ->where('post_id', $post->id)
                ->exists()
        ) {
            return redirect("/post/{$post->slug}");
        }

        Comment::create([
            'user_id' => $userId,
            'post_id' => $post->id,
            'score' => \request()->score ?? 0,
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
