<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Board;
use App\Models\Book;

class BoardController extends Controller
{
    public function saved()
    {
        $board = auth()->user()
            ->boards()
            ->where('title', 'Saved')
            ->first();

        if (!$board) {
            return response()->json([]);
        }

        return $board->books;
    }

   
    public function addBook(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);

        $user = auth()->user();

        $board = $user->boards()->firstOrCreate(
            ['title' => 'Saved'],
            ['description' => 'Saglabātās grāmatas']
        );

        $board->books()->syncWithoutDetaching([$request->book_id]);

        return response()->json([
            'message' => 'Book saved'
        ]);
    }

    public function removeBook($bookId)
    {
        $board = auth()->user()
            ->boards()
            ->where('title', 'Saved')
            ->first();

        if ($board) {
            $board->books()->detach($bookId);
        }

        return response()->json([
            'message' => 'Book removed'
        ]);
    }
}