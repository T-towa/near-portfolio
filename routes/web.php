<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/index', [PostController::class, 'index'])->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create'); // 投稿フォームの表示
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');  // 画像を含めた投稿の保存処理
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show'); // 投稿詳細画面の表示
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');  // 投稿一覧
    
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit'); // 投稿編集フォームの表示
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update'); // 投稿の更新処理
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy'); // 投稿の削除処理
    
    Route::get('/tags/{tag}', [PostController::class, 'showPostsByTag'])->name('posts.by.tag');

    // プロフィール関連のルート
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); // プロフィール編集フォームの表示
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // プロフィールの更新処理
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/search', [PostController::class, 'search'])->name('posts.search');//検索機能
});

Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

require __DIR__.'/auth.php';
