<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PostController::class,'show'])->name('posts');
Route::post('/', [PostController::class,'store']);

Route::get('post/{post:slug}', [CommentController::class, 'show']);
Route::post('post/{post:slug}', [CommentController::class,'store']);

Route::get('post/{post:slug}/edit', [PostController::class, 'edit']);
Route::post('post/{post:slug}/edit', [PostController::class, 'update']);

Route::get('post/{post:slug}/delete', [PostController::class, 'destroy']);

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
