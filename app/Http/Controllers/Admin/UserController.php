<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Vendor\Table;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $table = new Table(
            'users',
            'user',
            [
                'username',
                'full_name',
                'email',
                'deleted_at',
            ],
            [
                'username' => 'Username',
                'full_name' => 'Full name',
                'email' => 'Email',
            ],
            [
                'show',
                'edit',
                'block',
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

    public function edit($id = 0)
    {
        if ($id === 1 && Auth::user()->id !== 1) {
            return redirect()->route('admin.users.edit');
        }

        $obj = $id > 0 ? User::findOrFail($id) : new User();

        if (request()->isMethod('post')) {
            $post = request()->get('form', []);

            Validator::make($post, [
                'username' => 'required|string|max:255|unique:users,username,' . $obj->id . ',id',
                'full_name' => 'required|string|max:255',
                'password' => 'nullable|same:password_confirmation|string|min:8',
                'password_confirmation' => 'nullable|same:password|string|min:8',
                'email' => 'required|email|max:255|unique:users,email,' . $obj->id . ',id',
            ])->validate();

            if ($post['password']) {
                $post['password'] = Hash::make($post['password']);
            } else {
                unset($post['password']);
            }
            unset($post['password_confirmation']);

            DB::beginTransaction();
            try {
                $obj->fill($post);
                $obj->save();
                DB::commit();
            } catch (Exception $exception) {
                DB::rollBack();

                toastr()->error('Something went wrong');
                return redirect()->route('admin.users.edit', ['id' => $obj->id]);
            }

            toastr('User has been saved successfully');
            return redirect()->route('admin.users.edit', ['id' => $obj->id]);
        }

        return view('admin.user.edit', [
            'title' => 'Users',
            'obj' => $obj,
        ]);
    }

    public function delete($id)
    {
        if ($id === 1) {
            return redirect()->route('admin.users.index');
        }

        DB::beginTransaction();
        try {
            $obj = User::findOrFail($id);
            $obj->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            toastr()->error('Something went wrong');
            return redirect()->route('admin.users.index');
        }

        toastr('User deleted successfully');
        return redirect()->route('admin.users.index');
    }

    public function forceDelete($id)
    {
        if (Auth::user()->id !== 1) {
            return redirect()->route('admin.users.index');
        }

        DB::beginTransaction();
        try {
            $obj = User::withTrashed()->findOrFail($id);
            $obj->forceDelete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            toastr()->error('Something went wrong');
            return redirect()->route('admin.users.index');
        }

        toastr('User deleted permanently');
        return redirect()->route('admin.users.index');
    }

    public function restore($id)
    {
        if (Auth::user()->id !== 1) {
            return redirect()->route('admin.users.index');
        }

        DB::beginTransaction();
        try {
            $obj = User::withTrashed()->findOrFail($id);
            $obj->restore();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            toastr()->error('Something went wrong');
            return redirect()->route('admin.users.index');
        }

        toastr('User restored successfully');
        return redirect()->route('admin.users.index');
    }
}
