<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Book;
use App\Models\Favorite;
use App\Http\Requests\BookRequest;

class BookController extends Controller
{
    /**
     * 問題集の取得（ID指定）。
     *
     * @param integer $bookId
     * @return \Illuminate\Http\Response
     */
    public function getBook($bookId)
    {
        $user = Auth::user();
        $book = Book::findOrFail($bookId);

        $bookIsPublish = $book->book_is_publish;
        $bookIsMine = $user ? $user->user_id === $book->user_id : false;
        $bookHasCard = $book->cards()->count() !== 0;

        if (($bookIsPublish && $bookHasCard) || $bookIsMine) {
            return response()->json(['book' => $book], 200);
        }

        return redirect('/notfound');
    }

    /**
     * 問題集の取得（複数）。
     *
     * @param \Illuminate\Http\Request
     * @param integer $bookId
     * @return \Illuminate\Http\Response
     */
    public function getBooks(Request $request, $bookGroup)
    {
        $userId = Auth::id();
        $books = Book::query();

        if ($bookGroup === 'mines') {
            $books->where('user_id', $userId);
        }
        else if ($bookGroup === 'others') {
            $books->where([
                ['user_id', '<>', $userId],
                ['book_is_publish', '=', true]
            ])->has('cards');
        }
        else if ($bookGroup === 'favorites') {
            $favoriteBookIds = $userId ? Favorite::select('book_id')
                ->where('user_id', $userId) : [];
            $books->whereIn('book_id', $favoriteBookIds)
                ->where(function ($query) use ($userId) {
                    $query->where([
                        ['user_id', '<>', $userId],
                        ['book_is_publish', '=', true]
                    ])->has('cards')
                    ->orWhere('user_id', $userId);
                });
        }

        if (!empty($request->s)) {
            $books->where(function ($query) use ($request) {
                $query->where('book_title', 'like', '%'.$request->s.'%')
                    ->orWhere('book_desc', 'like', '%'.$request->s.'%');
            });
        }

        return response()->json($books->paginate(30), 200);
    }

    /**
     * 新規問題集作成。
     *
     * @param \App\Http\Requests\BookRequest
     * @return \Illuminate\Http\Response
     */
    public function createBook(BookRequest $request)
    {
        $userId = Auth::id();
        $book = new Book();
        $book->fill([
            'user_id' => $userId,
            'book_title' => $request->book_title,
            'book_desc' => $request->book_desc,
            'book_is_publish' => $request->book_is_publish
        ])->save();
        return response()->json(['book' => $book], 200);
    }

    /**
     * 問題集の更新。
     *
     * @param \App\Http\Requests\BookRequest
     * @param integer $bookId
     * @return \Illuminate\Http\Response
     */
    public function updateBook(BookRequest $request, $bookId)
    {
        $book = Book::findOrFail($bookId);
        Gate::authorize('edit-book', $book);
        $book->fill([
            'book_title' => $request->book_title,
            'book_desc' => $request->book_desc,
            'book_is_publish' => $request->book_is_publish
        ])->save();
        $book = Book::findOrFail($bookId);
        return response()->json(['book' => $book], 200);
    }

    /**
     * 問題集の削除。
     *
     * @param integer $bookId
     * @return \Illuminate\Http\Response
     */
    public function deleteBook($bookId)
    {
        $book = Book::findOrFail($bookId);
        Gate::authorize('edit-book', $book);
        $book->delete();
        return response()->json(['message' => 'Deleted.'], 200);
    }
}
