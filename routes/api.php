<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('stories')->group(function () {
    Route::post('/', [StoryController::class, 'store']); // POST /api/story/
    Route::get('/', [StoryController::class, 'index']); // GET /api/story/
    Route::get('/{id}', [StoryController::class, 'show']); // GET /api/story/{id}
});

Route::prefix('posts')->group(function () {
    Route::post('/', [PostController::class, 'store']); // POST /api/story/
    Route::get('/', [PostController::class, 'getAll']); // GET /api/story/
    Route::get('/{id}', [PostController::class, 'getId']); // GET /api/story/{id}
});
