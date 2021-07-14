<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

Route::get('/', [HomeController::class, 'index'])->name('home.index');


// --- Auth routes -- //

Route::get('/register', [RegisteredUserController::class, 'create'])
                ->middleware('guest')
                ->name('register.form');

Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest')
                ->name('register.process');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                ->middleware('guest')
                ->name('login.form');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest')
                ->name('login.process');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');                

// -- Article routes -- //

Route::post('/article', [ArticleController::class, 'store'])
    ->name('article.store');

Route::get('/article/create', [ArticleController::class, 'create'])
    ->name('article.create_form');

Route::get('/article/{hash_id}', [ArticleController::class, 'show'])
    ->name('article.show');

Route::post('/article/{hash_id}/rate', [ArticleController::class, 'rate'])
    ->name('article.rate');


// -- Comment routes -- //
Route::post('/comment', [CommentController::class, 'store'])
    ->name('comment.store');

Route::post('/comment/vote', [CommentController::class, 'vote'])
    ->name('comment.vote');


// -- Editor routes -- //
Route::get('editor/{uuid}', [EditorController::class, 'show'])
    ->name('editor.show');


// -- Profile routes -- //
Route::get('/user/profile', [ProfileController::class, 'show'])
    ->name('profile.show');