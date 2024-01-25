<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class UserController extends Controller
{
    public function show($username)
    {
        $loggedUser = Auth::user();
        $dbUser = User::with([]);

        if ($loggedUser && ($loggedUser->isAdmin())) {
            $dbUser->withTrashed();
        } else {
            $dbUser->where(function (Builder $q) {
                $q->where('banned_until', '<=', now());
                $q->orWhereNull('banned_until');
            });
        }
        $user = $dbUser->where('username', $username)->firstOrFail();

        if ($loggedUser && ($loggedUser->isAdmin())) {
            $dbComment = Comment::with([
                'post' => function (BelongsTo $query) {
                    $query->withTrashed();
                },
                'user' => function (BelongsTo $query) {
                    $query->withTrashed();
                }
            ]);
            $dbPost = Post::with([
                'user' => function (BelongsTo $query) {
                    $query->withTrashed();
                },
            ]);
            $dbPost->withTrashed();
            $dbComment->withTrashed();
        } else {
            $dbPost = Post::with(['user']);
            $dbComment = Comment::with(['post', 'user'])
                ->whereHas('post', function (Builder $query) {
                    $query->where('is_published', 1);
                });
        }

        $posts = $dbPost
            ->where('user_id', $user->id)
            ->when(
                !$loggedUser || (!$loggedUser->isAdmin() && $loggedUser->id != $user->id),
                function (Builder $query) {
                    $query->where('is_published', 1);
                }
            )
            ->orderByDesc('created_at')
            ->get();

        $comments = $dbComment
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('user', [
            'user' => $user,
            'publishedPosts' => $posts->where('is_published', 1),
            'notPublishedPosts' => $posts->where('is_published', 0),
            'comments' => $comments,
        ]);
    }

    public function edit_details()
    {
        return view('edit-user-details');
    }

    public function update_details()
    {
        \request()->validate([
            'full_name' => ['required', 'string', 'max:255']
        ]);

        if (\request()->username != Auth::user()->username)
            \request()->validate([
                'username' => ['required', 'alpha_dash', 'min:3', 'max:127', 'unique:users'],
            ]);

        Auth::user()->update([
            'username' => \request()->username,
            'full_name' => \request()->full_name,
        ]);

        return redirect("/user/" . Auth::user()->username);
    }

    public function edit_email()
    {
        return view('edit-email');
    }

    public function update_email()
    {
        if (\request()->email != Auth::user()->email) {
            \request()->validate([
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);

            if (Hash::check(\request()->password, Auth::user()->password)) {
                Auth::user()->update([
                    'email' => \request()->email,
                    'email_verified_at' => null,
                ]);

                return redirect('email/verify');
            } else
                return redirect()->back()->withInput()->withErrors(['password' => 'The provided password is incorrect.']);
        }
    }

    public function edit_password()
    {
        return view('edit-password');
    }

    public function update_password()
    {
        \request()->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if (Hash::check(\request()->old_password, Auth::user()->password)) {
            Auth::user()->update([
                'password' => Hash::make(\request()->password)
            ]);

            return redirect("/user/" . Auth::user()->username);
        } else
            return redirect()->back()->withInput()->withErrors(['old_password' => 'The provided password is incorrect.']);
    }

    public function confirm_destroy()
    {
        return view('delete-user');
    }

    public function destroy()
    {
        \request()->validate([
            'password' => ['required', 'string', 'min:8']
        ]);

        if (Hash::check(\request()->password, Auth::user()->password)) {
            $user = Auth::user();

            Auth::logout();

            if ($user->delete())
                return redirect('/')->withDanger('You account has been deleted!');;
        }
        return redirect()->back()->withInput()->withErrors(['password' => 'The provided password is incorrect.']);
    }
}
