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
            'posts' =>
                Post::with('user')
                    ->whereDoesntHave('user', function ($query) {
                        $query->where('banned_until', '>', now());
                    })
                    ->where('is_published', 1)
                    ->latest()
                    ->paginate(10)
        ]);
    }

    public function store()
    {
        \request()->validate([
            'title' => ['required', 'string', 'min:4', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:255', 'unique:posts'],
            'excerpt' => ['required', 'string', 'min:4'],
            'body' => ['required', 'string', 'min:32'],
            'post_image' => ['image'],
            'gallery.*' => ['image']
        ]);

        $attributes = [
            'user_id' => Auth::id(),
            'title' => \request()->title,
            'slug' => \request()->slug,
            'excerpt' => \request()->excerpt,
            'body' => \request()->body,
            'is_published' => \request()->is_published,
        ];

        if (\request()->post_image) {
            $post_image = \request()->file('post_image');
            $post_image_name = $post_image->getClientOriginalName();
            $attributes['post_image'] = $post_image_name;
        }

        $post_id = Post::create($attributes)->id;

        if (\request()->post_image) {
            Storage::putFileAs(
                "public/photos/$post_id/post_image", $post_image, $post_image_name
            );
        }

        $gallery = \request('gallery');
        if ($gallery) {
            foreach ($gallery as $image) {
                Storage::putfile("public/photos/$post_id/post_gallery", $image);
            }
        }

        return redirect('/');
    }

    public function edit(Post $post)
    {
        if (Auth::id() == $post->user->id) {
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
            'excerpt' => ['required', 'string', 'min:4'],
            'body' => ['required', 'string', 'min:32'],
            'post_image' => ['image'],
        ]);

        $attributes = [
            'title' => \request()->title,
            'excerpt' => \request()->excerpt,
            'body' => \request()->body,
            'is_published' => \request()->is_published,
        ];

        if (\request()->file('post_image')) {
            $post_image = \request()->file('post_image');
            $post_image_name = $post_image->getClientOriginalName();

            $path = "photos/$post->id/post_image/$post->post_image";
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            Storage::putFileAs(
                "public/photos/$post->id/post_image", $post_image, $post_image_name
            );

            $attributes['post_image'] = $post_image_name;
        }

        $post->update($attributes);

        return redirect("post/$post->slug");
    }


    public function destroy_post_image(Post $post)
    {
        if (Auth::id() == $post->user->id) {
            Storage::deleteDirectory("public/photos/$post->id/post_image");
            $post->update([
                'post_image' => null,
            ]);
            return redirect("post/$post->slug");
        } else
            abort(404);
    }

    public function destroy_gallery(Post $post)
    {
        if (Auth::id() == $post->user->id) {
            Storage::deleteDirectory("public/photos/$post->id/post_gallery");
            return redirect("post/$post->slug");
        } else
            abort(404);
    }

    public function destroy(Post $post)
    {
        if (Auth::id() == $post->user->id) {
            $post->delete();
            Storage::disk('public')->deleteDirectory("photos/$post->id");
            return redirect('/');
        } else
            abort(403);
    }

    public function fetchGallery(int $id)
    {
        $files = Storage::disk('public')->files("/photos/$id/post_gallery");
        $files = array_chunk($files, 4);

        return response()->json(view('gallery', compact('files'))->render());
    }

    public function addToGallery(int $id)
    {
        $files = \request('files');

        foreach ($files as $file) {
            Storage::putfile("public/photos/$id/post_gallery", $file);
        }

        return response()->json();
    }

    public function deleteFromGallery()
    {
        $path = \request()->post('path');

        try {
            Storage::delete('public/' . $path);
        } catch (\Exception $e) {
        }

        return response()->json();
    }
}
