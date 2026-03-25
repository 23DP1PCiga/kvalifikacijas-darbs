<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\ReviewController;
use App\Models\Book;

Route::post('/register', [AuthController::class, 'register']);

Route::get('api/books', [BookController::class, 'index']);

Route::get('/api/books/search', [BookController::class, 'search']);

Route::get('/api/books/{book}', [BookController::class, 'show']);

Route::get('/api/books/{id}/comments', [ReviewController::class, 'index']);
Route::post('/api/comments', [ReviewController::class, 'store']);






Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');