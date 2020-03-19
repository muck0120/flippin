<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Book;

class FavoriteController extends Controller
{
    /**
     * お気に入りの新規追加。
     *
     * @param integer $bookId
     * @return \Illuminate\Http\Response
     */
    public function createFavorite($bookId)
    {
        Book::findOrFail($bookId);
        $userId = Auth::user()->user_id;

        $favoriteIsExisted = Favorite::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->exists();
        if ($favoriteIsExisted) {
            return response()->json(['message' => 'Existed.'], 200);
        }

        $favorite = new Favorite();
        $favorite->fill([
            'user_id' => $userId,
            'book_id' => $bookId
        ])->save();
        return response()->json(['message' => 'Created.'], 200);
    }

    /**
     * お気に入りの削除。
     *
     * @param integer $bookId
     * @return \Illuminate\Http\Response
     */
    public function deleteFavorite($bookId)
    {
        $userId = Auth::user()->user_id;
        $favorite = Favorite::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->firstOrFail();
        $favorite->delete();
        return response()->json(['message' => 'Deleted.'], 200);
    }
}
