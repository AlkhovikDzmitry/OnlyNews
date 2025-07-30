<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


use App\Http\Controllers\{
    AuthController,
    HomeController,
    AboutController,
    ContactController,
    ProfileController,
    PostController,
    FeedbackController,
    CategoryController,
    CommentController
};



// Маршруты сброса пароля
Route::get('password/reset', [AuthController::class, 'showLinkRequestForm'])
     ->name('password.request');

Route::post('password/email', [AuthController::class, 'sendResetLinkEmail'])
     ->name('password.email');

Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
     ->name('password.reset');

Route::post('password/reset', [ResetPasswordController::class, 'reset'])
     ->name('password.update');


// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Статические страницы
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

// Показ постов по категории
Route::get('/category/{category:slug}', [CategoryController::class, 'show'])
    ->name('category.show');



// Аутентификация
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');
});

// Защищенные маршруты
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    
    // Маршруты для управления постами
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
});



Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/posts', [\App\Http\Controllers\Admin\PostController::class, 'index'])->name('admin.posts.index');
    Route::post('/posts/{post}/approve', [\App\Http\Controllers\Admin\PostController::class, 'approve'])->name('admin.posts.approve');
    Route::post('/posts/{post}/reject', [\App\Http\Controllers\Admin\PostController::class, 'reject'])->name('admin.posts.reject');
});