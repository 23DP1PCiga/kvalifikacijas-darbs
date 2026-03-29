<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\ReviewController;

Route::get('/books', [BookController::class, 'index']);

Route::get('/books/search', [BookController::class, 'search']);
Route::get('/books/{book}', [BookController::class, 'show']);

Route::get('/books/{id}/comments', [ReviewController::class, 'index']);
Route::post('/comments', [ReviewController::class, 'store'])->middleware('auth:sanctum');
