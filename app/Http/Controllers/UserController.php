<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->first();

        if ($user) {
            return view('user', [
                'user' => $user
            ]);
        } else
            abort(404);
    }

    public function edit_details($username)
    {
        $user = User::where('username', $username)->first();

        if ($user) {
            if ($user->id == Auth::id()) {
                return view('edit-user-details', [
                    'user' => $user
                ]);
            } else
                abort(403);
        } else
            abort(404);
    }

    public function update_details($username)
    {
        $user = User::where('username', $username)->first();

        if ($user) {
            if ($user->id == Auth::id()) {
                \request()->validate([
                    'full_name' => ['required', 'string', 'max:255']
                ]);

                if (\request()->username != $user->username)
                    \request()->validate([
                        'username' => ['required', 'alpha_dash', 'min:3', 'max:127', 'unique:users'],
                    ]);

                $user->update([
                    'username' => \request()->username,
                    'full_name' => \request()->full_name,
                ]);

                return redirect('/');
            } else
                abort(403);
        } else
            abort(404);
    }
}
