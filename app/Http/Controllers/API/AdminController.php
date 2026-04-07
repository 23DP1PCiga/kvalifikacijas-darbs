<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Review;
use App\Models\User;

class AdminController extends Controller
{
    public function storeBook(Request $request)
{
    $data = $request->validate([
        'title' => 'required|max:150',
        'author' => 'required|max:150',
        'genre' => 'nullable',
        'description' => 'nullable',
        'price' => 'nullable|numeric',
        'publishing_year' => 'nullable|integer',
        'cover' => 'nullable|image|max:2048'
    ]);

   
    if ($request->hasFile('cover')) {
        $data['cover'] = $request->file('cover')->store('books', 'public');
    }

    return Book::create($data);
}

    public function updateBook(Request $request, $id)
{
    $book = Book::findOrFail($id);

    $data = $request->validate([
        'title' => 'required|max:150',
        'author' => 'required|max:150',
        'genre' => 'nullable',
        'description' => 'nullable',
        'price' => 'nullable|numeric',
        'publishing_year' => 'nullable|integer',
        'cover' => 'nullable|image|max:2048'
    ]);

    if ($request->hasFile('cover')) {
        $data['cover'] = $request->file('cover')->store('books', 'public');
    }

    $book->update($data);
    return $book;
    }

    public function deleteBook($id)
    {
        Book::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }

    public function deleteReview($id)
    {
        Review::destroy($id);
        return response()->json(['message' => 'Review deleted']);
    }

    public function stats()
{
    return response()->json([
        'books' => Book::count(),
        'users' => User::count(),
        'reviews' => Review::count(),

        'avg_rating' => round(
            Review::whereNotNull('rating')->avg('rating'),
            1
        ),

        'top_book' => Book::withCount('reviews')
            ->orderByDesc('reviews_count')
            ->first()
    ]);
}

public function chartData()
{
    $books = \App\Models\Book::withCount('reviews')
        ->orderByDesc('reviews_count')
        ->take(5)
        ->get();

    return response()->json([
        'labels' => $books->pluck('title'),
        'data' => $books->pluck('reviews_count')
    ]);
}

public function ratingsDistribution()
{
    $ratings = \App\Models\Review::selectRaw('rating, COUNT(*) as count')
        ->whereNotNull('rating')
        ->groupBy('rating')
        ->orderBy('rating')
        ->get();

    $labels = [1, 2, 3, 4, 5];
    $data = [];

    foreach ($labels as $r) {
        $found = $ratings->firstWhere('rating', $r);
        $data[] = $found ? $found->count : 0;
    }

    return response()->json([
        'labels' => $labels,
        'data' => $data
    ]);
}
}