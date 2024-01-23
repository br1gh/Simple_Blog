<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Vendor\Table;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index()
    {
        $table = new Table(
            'posts',
            'post',
            [
                'slug',
                'title',
                'users.username',
                'posts.deleted_at',
            ],
            [
                'slug' => 'Slug',
                'title' => 'Title',
            ],
            [
                'show',
                'ban',
                'restore',
                'delete',
                'forceDelete',
            ]
        );

        if (request()->ajax()) {
            return response()->json($table->render());
        }

        return view('layouts.admin.index.index', [
            'table' => $table
        ]);
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $obj = Post::findOrFail($id);
            $obj->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            toastr()->error('Something went wrong');
            return redirect()->route('admin.posts.index');
        }

        toastr('Post deleted successfully');
        return redirect()->route('admin.posts.index');
    }

    public function forceDelete($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.posts.index');
        }

        DB::beginTransaction();
        try {
            $obj = Post::withTrashed()->findOrFail($id);
            $obj->forceDelete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            toastr()->error('Something went wrong');
            return redirect()->route('admin.posts.index');
        }

        toastr('Post deleted permanently');
        return redirect()->route('admin.posts.index');
    }

    public function restore($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.posts.index');
        }

        DB::beginTransaction();
        try {
            $obj = Post::withTrashed()->findOrFail($id);
            $obj->restore();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            toastr()->error('Something went wrong');
            return redirect()->route('admin.posts.index');
        }

        toastr('Post restored successfully');
        return redirect()->route('admin.posts.index');
    }
}
