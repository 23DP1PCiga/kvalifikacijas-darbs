<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function search(Request $request)
{
    $query = $request->input('q');

    if (!$query) {
        return response()->json([]);
    }

    return Book::where('title', 'LIKE', "%{$query}%")
        ->orWhere('author', 'LIKE', "%{$query}%")
        ->get();
}

    public function index()
    {
        return Book::withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->get();
    }

    public function show(Book $book)
    {
        return $book->loadAvg('ratings', 'rating')
                ->loadCount('ratings');
}
}
