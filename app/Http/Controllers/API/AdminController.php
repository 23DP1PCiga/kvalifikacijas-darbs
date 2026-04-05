<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Review;

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
}