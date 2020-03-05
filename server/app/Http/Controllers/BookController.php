<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Book;
use App\Http\Requests\BookRequest;

class BookController extends Controller
{
    /**
     * 問題集の取得（ID指定）。
     *
     * @param \Illuminate\Http\Request
     * @param integer $bookId
     * @return \Illuminate\Http\Response
     */
    public function getBook($bookId)
    {
        $user = Auth::user();
        $book = Book::findOrFail($bookId);

        $bookIsPublish = $book->book_is_publish;
        $bookIsMine = $user ? $user->user_id === $book->user_id : false;

        if ($bookIsPublish || $bookIsMine) {
            return response()->json(['book' => $book], 200);
        }

        return redirect('/notfound');
    }

    /**
     * 新規問題集作成。
     *
     * @param \App\Http\Requests\BookRequest
     * @return \Illuminate\Http\Response
     */
    public function createBook(BookRequest $request)
    {
        $userId = Auth::user()->user_id;
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
