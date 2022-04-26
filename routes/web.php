<?php

use App\Http\Controllers\UserController;
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

Route::get('/', [PostController::class, 'show'])->name('posts');
Route::post('/', [PostController::class, 'store']);

Route::get('user/{user:username}', [UserController::class, 'show']);

Route::get('user/{user:username}/edit-details', [UserController::class, 'edit_details'])->middleware('auth');
Route::post('user/{user:username}/edit-details', [UserController::class, 'update_details'])->middleware('auth');

Route::get('post/{post:slug}', [CommentController::class, 'show']);
Route::post('post/{post:slug}', [CommentController::class, 'store']);

Route::get('post/{post:slug}/edit', [PostController::class, 'edit'])->middleware('auth');
Route::post('post/{post:slug}/edit', [PostController::class, 'update'])->middleware('auth');

Route::get('post/{post:slug}/delete', [PostController::class, 'destroy'])->middleware('auth');

Route::get('post/{post:slug}/comment/{comment:id}/edit', [CommentController::class, 'edit'])->middleware('auth');
Route::post('post/{post:slug}/comment/{comment:id}/edit', [CommentController::class, 'update'])->middleware('auth');

Route::get('post/{post:slug}/comment/{comment:id}/delete', [CommentController::class, 'destroy'])->middleware('auth');

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
