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
    private $type;

    public function __construct()
    {
        $this->type = request()->segment(3);
    }

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
            ],
            [],
            null,
            $this->type === 'published'
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
        if ($id == 1) {
            toastr()->error('You can not delete rules');
            return redirect()->back();
        }

        $obj = Post::findOrFail($id);

        DB::beginTransaction();
        try {
            $obj->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            toastr()->error('Something went wrong');
            return redirect()->back();
        }

        toastr('Post deleted successfully');
        return redirect()->back();
    }

    public function forceDelete($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->back();
        }

        $obj = Post::withTrashed()->findOrFail($id);

        DB::beginTransaction();
        try {
            $obj->forceDelete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            toastr()->error('Something went wrong');
            return redirect()->back();
        }

        toastr('Post deleted permanently');
        return redirect()->back();
    }

    public function restore($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->back();
        }

        $obj = Post::withTrashed()->findOrFail($id);

        DB::beginTransaction();
        try {
            $obj->restore();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            toastr()->error('Something went wrong');
            return redirect()->back();
        }

        toastr('Post restored successfully');
        return redirect()->back();
    }
}
