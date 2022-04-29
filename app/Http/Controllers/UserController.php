<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
