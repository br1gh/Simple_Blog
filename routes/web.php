<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Auth::routes(['verify' => true]);

Route::get('/', [PostController::class, 'show']);
Route::post('/', [PostController::class, 'store']);

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('user/{user:username}', [UserController::class, 'show'])->name('user');
Route::get('post/{post:slug}', [CommentController::class, 'show'])->name('post');
Route::post('post/{post:slug}', [CommentController::class, 'store']);

Route::middleware('auth')
    ->group(function () {
        Route::get('edit-details', [UserController::class, 'edit_details']);
        Route::post('edit-details', [UserController::class, 'update_details']);

        Route::get('edit-email', [UserController::class, 'edit_email']);
        Route::post('edit-email', [UserController::class, 'update_email']);

        Route::get('edit-password', [UserController::class, 'edit_password']);
        Route::post('edit-password', [UserController::class, 'update_password']);

        Route::get('delete-account', [UserController::class, 'confirm_destroy']);
        Route::post('delete-account', [UserController::class, 'destroy']);

        Route::get('post/{post:slug}/edit', [PostController::class, 'edit']);
        Route::post('post/{post:slug}/edit', [PostController::class, 'update']);

        Route::get('post/{post:slug}/delete', [PostController::class, 'destroy']);
        Route::get('post/{post:slug}/delete-gallery', [PostController::class, 'destroy_gallery']);
        Route::get('post/{post:slug}/delete-post-image', [PostController::class, 'destroy_post_image']);

        Route::get('post/{post:slug}/comment/{comment:id}/edit', [CommentController::class, 'edit']);
        Route::post('post/{post:slug}/comment/{comment:id}/edit', [CommentController::class, 'update']);

        Route::get('post/{post:slug}/comment/{comment:id}/delete', [CommentController::class, 'destroy']);

        Route::get('/report/{type}/{id}', [ReportController::class, 'report'])->name('report');
    });

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', function () {
            return view('layouts.admin.app');
        });

        Route::name('users.')
            ->prefix('users')
            ->group(function () {
                Route::get('/', [Admin\UserController::class, 'index'])->name('index');
                Route::match(['get', 'post'], '/edit/{id?}', [Admin\UserController::class, 'edit'])->name('edit');
                Route::get('/delete/{id}', [Admin\UserController::class, 'delete'])->name('delete');
                Route::get('/force-delete/{id}', [Admin\UserController::class, 'forceDelete'])->name('force-delete');
                Route::get('/restore/{id}', [Admin\UserController::class, 'restore'])->name('restore');
            });

        Route::name('posts.')
            ->prefix('posts')
            ->group(function () {
                Route::get('/', [Admin\PostController::class, 'index'])->name('index');
                Route::get('/delete/{id}', [Admin\PostController::class, 'delete'])->name('delete');
                Route::get('/force-delete/{id}', [Admin\PostController::class, 'forceDelete'])->name('force-delete');
                Route::get('/restore/{id}', [Admin\PostController::class, 'restore'])->name('restore');
            });

        Route::name('reports.')
            ->prefix('reports')
            ->group(function () {
                Route::get('/', [Admin\ReportController::class, 'index'])->name('index');
//                Route::match(['get', 'post'], '/enforce/{id?}', [Admin\UserController::class, 'enforce'])->name('enforce');
            });
    });
