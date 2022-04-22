<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Post;

class UserController extends Controller
{
    public function show($username)
    {
        return view('user', [
            'user' => User::where('username', $username)->first()
        ]);
    }
}
