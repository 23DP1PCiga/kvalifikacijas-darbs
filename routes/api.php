<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\BoardController;
use App\Http\Controllers\API\AdminController;

Route::get('/books', [BookController::class, 'index']);

Route::get('/books/search', [BookController::class, 'search']);
Route::get('/books/{book}', [BookController::class, 'show']);

Route::get('/books/{id}/comments', [ReviewController::class, 'index']);
Route::post('/comments', [ReviewController::class, 'store'])->middleware('auth');

Route::get('/saved-books', [BoardController::class, 'saved']);
Route::post('/saved-books', [BoardController::class, 'addBook']);
Route::delete('/saved-books/{id}', [BoardController::class, 'removeBook']);

Route::get('/my-comments', [ReviewController::class, 'myComments']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    Route::post('/admin/books', [AdminController::class, 'storeBook']);
    Route::put('/admin/books/{id}', [AdminController::class, 'updateBook']);
    Route::delete('/admin/books/{id}', [AdminController::class, 'deleteBook']);

    Route::delete('/admin/reviews/{id}', [AdminController::class, 'deleteReview']);

});
