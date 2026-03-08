<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);

Route::get('/api/books', function () {
    return \App\Models\Book::withCount('ratings')
        ->withAvg('ratings', 'rating')
        ->get();
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
