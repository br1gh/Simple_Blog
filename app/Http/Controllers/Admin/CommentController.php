<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Vendor\Table;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function index()
    {
        $table = new Table(
            'comments',
            'comment',
            [
                'comments.body',
                'score',
                'users.username',
                'comments.deleted_at',
                'posts.title',
                'posts.slug',
            ],
            [
                'body' => 'Body',
                'score' => 'Score',
            ],
            [
                'ban',
                'restore',
                'delete',
                'forceDelete',
            ],
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
        $obj = Comment::findOrFail($id);

        DB::beginTransaction();
        try {
            $obj->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            toastr()->error('Something went wrong');
            return redirect()->back();
        }

        toastr('Comment deleted successfully');
        return redirect()->back();
    }

    public function forceDelete($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->back();
        }

        $obj = Comment::withTrashed()->findOrFail($id);

        DB::beginTransaction();
        try {
            $obj->forceDelete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            toastr()->error('Something went wrong');
            return redirect()->back();
        }

        toastr('Comment deleted permanently');
        return redirect()->back();
    }

    public function restore($id)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->back();
        }

        $obj = Comment::withTrashed()->findOrFail($id);

        DB::beginTransaction();
        try {
            $obj->restore();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            toastr()->error('Something went wrong');
            return redirect()->back();
        }

        toastr('Comment restored successfully');
        return redirect()->back();
    }
}
