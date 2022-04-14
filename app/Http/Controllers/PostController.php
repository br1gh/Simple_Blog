<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'slug' => ['required', 'alpha_dash', 'max:255', 'unique:posts'],
            'excerpt' => ['required', 'string', 'min:4', 'max:255'],
            'body' => ['required', 'string', 'min:32'],
            'post_image' => ['image'],
            'gallery.*' => ['image']
        ]);

        $post_image = \request()->file('post_image');
        $post_image_name = $post_image->getClientOriginalName();

        $post_id = Post::create([
            'user_id' => Auth::user()->id,
            'title' => \request()->title,
            'slug' => \request()->slug,
            'excerpt' => \request()->excerpt,
            'body' => \request()->body,
            'post_image' => $post_image_name
        ])->id;

        $path = Storage::putFileAs(
            "public/photos/$post_id/post_image", $post_image, $post_image_name
        );

        return redirect('/');
    }

    public function edit(Post $post)
    {
        if (Auth::user()->id == $post->user->id) {
            return view('edit-post', [
                'post' => $post
            ]);
        } else
            abort(403);
    }

    public function update(Post $post)
    {
        \request()->validate([
            'title' => ['required', 'string', 'min:4', 'max:255'],
            'excerpt' => ['required', 'string', 'min:4', 'max:255'],
            'body' => ['required', 'string', 'min:32'],
            'post_image' => ['image'],
        ]);

        if (\request()->file('post_image')) {
            $post_image = \request()->file('post_image');
            $post_image_name = $post_image->getClientOriginalName();

            $path = "/photos/$post->id/post_image/$post->post_image";
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            Storage::putFileAs(
                "public/photos/$post->id/post_image", $post_image, $post_image_name
            );
        } else
            $post_image_name = $post->post_image;

        $post->update([
            'title' => \request()->title,
            'excerpt' => \request()->excerpt,
            'body' => \request()->body,
            'post_image' => $post_image_name
        ]);

        return redirect('/');
    }

    public function destroy(Post $post)
    {
        if (Auth::user()->id == $post->user->id) {
            $post->delete();
            return redirect('/');
        } else
            abort(403);
    }
}
