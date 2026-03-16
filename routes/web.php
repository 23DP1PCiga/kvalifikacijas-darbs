<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Models\Book;

Route::post('/register', [AuthController::class, 'register']);

Route::get('/api/books', function () {
    return \App\Models\Book::withCount('ratings')
        ->withAvg('ratings', 'rating')
        ->get();
});

Route::get('/books/search', function (Request $request) {
    $query = $request->q;
    return Book::where('title', 'LIKE', "%{$query}%")
        ->orWhere('author', 'LIKE', "%{$query}%")
        ->get();
});










Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');