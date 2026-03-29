<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\ReviewController;
use App\Models\Book;

Route::post('/register', [AuthController::class, 'register']);
Route::get('/user', [AuthController::class, 'user']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/{any}', function () {
   return view('app');
})->where('any', '.*');