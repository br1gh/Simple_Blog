<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function show()
    {
        return view('welcome', [
            'posts' => Post::latest()->paginate(10)
        ]);
    }

    public function store()
    {
        \request()->validate([
            'title' => ['required', 'string', 'min:4', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:255'],
            'excerpt' => ['required', 'string', 'min:4', 'max:255'],
            'body' => ['required', 'string', 'min:32'],
        ]);

        Post::create([
            'user_id' => Auth::user()->id,
            'title' => \request()->title,
            'slug' => \request()->slug,
            'excerpt' => \request()->excerpt,
            'body' => \request()->body
        ]);

        return redirect('/');
    }

    public function edit($slug)
    {
        $post = Post::where('slug',$slug)->get()[0];

        if (Auth::check() and Auth::user()->id == $post->user->id)
        {
            return view('edit-post', [
                'post' => $post
            ]);
        }
        else
            abort(403);
    }

    public function update($slug)
        {
            \request()->validate([
                'title' => ['required', 'string', 'min:4', 'max:255'],
                'excerpt' => ['required', 'string', 'min:4', 'max:255'],
                'body' => ['required', 'string', 'min:32'],
            ]);

            Post::where('slug',$slug)
                ->update([
                'title' => \request()->title,
                'excerpt' => \request()->excerpt,
                'body' => \request()->body
            ]);

            return redirect('/');
        }

    public function destroy($slug)
    {
        Post::where('slug', $slug)->delete();

        return redirect('/');
    }
}
