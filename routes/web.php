<?php

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
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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

    // Маршруты сброса пароля
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Защищенные маршруты
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

    // Загрузка изображений в редакторе
    Route::post('/posts/upload-image', [PostController::class, 'uploadImage'])->name('posts.upload-image');

    // Маршруты для управления постами
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
});

// Модерация
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/posts', [AdminPostController::class, 'index'])->name('admin.posts.index');
    Route::post('/posts/{post}/approve', [AdminPostController::class, 'approve'])->name('admin.posts.approve');
    Route::post('/posts/{post}/reject', [AdminPostController::class, 'reject'])->name('admin.posts.reject');
});
