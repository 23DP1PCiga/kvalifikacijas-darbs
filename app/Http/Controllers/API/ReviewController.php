<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
     public function index($bookId)
    {
        return Review::where('book_id', $bookId)
            ->whereNotNull('comment')
            ->where('comment', '!=', '')
            ->latest()
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'comment' => 'required|string|max:1000',
            'rating' => 'nullable|numeric|min:0|max:5'
        ]);

        $validated['user_id'] = 1;

        return Review::create($validated);
    }
}
