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

    public function store(Request $request)
    {
        return auth()->user()->boards()->create(
            $request->validate([
                'title' => 'required|max:150',
                'description' => 'nullable'
            ])
        );
    }

     public function index()
    {
        return auth()->user()
            ->boards()
            ->where('title', '!=', 'Saved')
            ->with('books')
            ->get();
    }

    public function addToBoard(Request $request)
    {
        $request->validate([
            'board_id' => 'required|exists:boards,id',
            'book_id' => 'required|exists:books,id'
        ]);

        $board = auth()->user()->boards()->findOrFail($request->board_id);
        $board->books()->syncWithoutDetaching([$request->book_id]);
        return response()->json(['message' => 'Added']);
    }

    public function show($id)
    {
        $board = auth()->user()
            ->boards()
            ->with('books')
            ->findOrFail($id);
        return $board;
    }

    public function removeFromBoard($boardId, $bookId)
    {
        $board = auth()->user()->boards()->findOrFail($boardId);
        $board->books()->detach($bookId);
        return response()->json(['message' => 'Removed']);
    }

    public function update(Request $request, $id)
    {
        $board = auth()->user()->boards()->findOrFail($id);

        $board->update($request->validate([
            'title' => 'required|max:150',
            'description' => 'nullable'
        ]));

        return $board;
    }

    public function destroy($id)
    {
        $board = auth()->user()->boards()->findOrFail($id);
        $board->delete();
        return response()->json(['message' => 'Deleted']);
    }
}