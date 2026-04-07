<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
     public function index($bookId)
    {
         return Review::with('user') 
        ->where('book_id', $bookId)
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
            'rating' => 'required|numeric|min:1|max:5'
        ]);

        return Review::create([
            ...$validated,
            'user_id' => auth()->id()
        ]);
    }

        public function myComments()
        {
            return \App\Models\Review::with('book')
                ->where('user_id', auth()->id())
                ->whereNotNull('comment')
                ->where('comment', '!=', '')
                ->latest()
                ->get();
        }

        public function myRatings()
        {
            return \App\Models\Review::with('book')
                ->where('user_id', auth()->id())
                ->whereNotNull('rating')
                ->latest()
                ->get();
        }

        public function destroy($id)
        {
            $review = Review::findOrFail($id);

            if ($review->user_id !== auth()->id()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            $review->delete();

            return response()->json(['message' => 'Deleted']);
        }
}
