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

Route::get('/', [PostController::class, 'show']);
Route::post('/', [PostController::class, 'store']);

Route::get('user/{user:username}', [UserController::class, 'show']);

Route::get('edit-details', [UserController::class, 'edit_details'])->middleware('auth');
Route::post('edit-details', [UserController::class, 'update_details'])->middleware('auth');

Route::get('edit-email', [UserController::class, 'edit_email'])->middleware('auth');
Route::post('edit-email', [UserController::class, 'update_email'])->middleware('auth');

Route::get('edit-password', [UserController::class, 'edit_password'])->middleware('auth');
Route::post('edit-password', [UserController::class, 'update_password'])->middleware('auth');

Route::get('delete-account', [UserController::class, 'confirm_destroy'])->middleware('auth');
Route::post('delete-account', [UserController::class, 'destroy'])->middleware('auth');

Route::get('post/{post:slug}', [CommentController::class, 'show']);
Route::post('post/{post:slug}', [CommentController::class, 'store']);

Route::get('post/{post:slug}/edit', [PostController::class, 'edit'])->middleware('auth');
Route::post('post/{post:slug}/edit', [PostController::class, 'update'])->middleware('auth');

Route::get('post/{post:slug}/delete', [PostController::class, 'destroy'])->middleware('auth');
Route::get('post/{post:slug}/delete-gallery', [PostController::class, 'destroy_gallery'])->middleware('auth');
Route::get('post/{post:slug}/delete-post-image', [PostController::class, 'destroy_post_image'])->middleware('auth');

Route::get('post/{post:slug}/comment/{comment:id}/edit', [CommentController::class, 'edit'])->middleware('auth');
Route::post('post/{post:slug}/comment/{comment:id}/edit', [CommentController::class, 'update'])->middleware('auth');

Route::get('post/{post:slug}/comment/{comment:id}/delete', [CommentController::class, 'destroy'])->middleware('auth');

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
