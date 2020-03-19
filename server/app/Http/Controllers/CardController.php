<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardRequest;
use App\Http\Requests\CardOrderRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Models\Book;
use App\Models\Card;

class CardController extends Controller
{
    /**
     * 画像ファイルの取得。
     *
     * @param integer $cardId
     * @param string $fileName
     * @return \Illuminate\Http\Response
     */
    public function getImageFile($cardId, $fileName)
    {
        $path = 'public/images/cards/'.$cardId.'/'.$fileName;
        $mimeType = Storage::mimeType($path);
        $imageFile = Storage::get($path);
        $headers = ['Content-Type' => $mimeType];
        return response()->make($imageFile, 200, $headers);
    }

    /**
     * 新規問題の作成。
     *
     * @param \App\Http\Requests\CardRequest
     * @param integer $bookId
     * @return \Illuminate\Http\Response
     */
    public function createCard(CardRequest $request, $bookId)
    {
        $book = Book::findOrFail($bookId);
        Gate::authorize('edit-book', $book);

        $order = Card::where('book_id', $bookId)->max('card_order');

        $card = new Card();
        $card->fill([
            'book_id' => $bookId,
            'card_order' => $order + 1,
            'card_question' => $request->card_question,
            'card_explanation' => $request->card_explanation
        ])->save();

        $card->choices()->createMany($request->card_choices);

        if ($request->hasFile('card_question_image')) {
            $path = $request->file('card_question_image')
                ->store('public/images/cards/'.$card->card_id);
            $card->fill(['card_question_image' => basename($path)])->save();
        }

        if ($request->hasFile('card_explanation_image')) {
            $path = $request->file('card_explanation_image')
                ->store('public/images/cards/'.$card->card_id);
            $card->fill(['card_explanation_image' => basename($path)])->save();
        }

        return response()->json(['card' => $card], 200);
    }

    /**
     * 既存問題の更新。
     *
     * @param \App\Http\Requests\CardRequest
     * @param integer $bookId
     * @param integer $cardId
     * @return \Illuminate\Http\Response
     */
    public function updateCard(CardRequest $request, $bookId, $cardId)
    {
        $book = Book::findOrFail($bookId);
        Gate::authorize('edit-book', $book);

        $card = Card::where([
            'card_id' => $cardId,
            'book_id' => $bookId
        ])->firstOrFail();

        $card->fill([
            'card_question' => $request->card_question,
            'card_explanation' => $request->card_explanation
        ])->save();

        $card->choices()->delete();
        $card->choices()->createMany($request->card_choices);

        $card->updateQuestionImage($request);
        $card->updateExplanationImage($request);

        return response()->json(['card' => $card], 200);
    }

    /**
     * 既存問題の順番更新。
     *
     * @param \App\Http\Requests\CardOrderRequest
     * @param integer $bookId
     * @return \Illuminate\Http\Response
     */
    public function updateCardOrder(CardOrderRequest $request, $bookId)
    {
        $book = Book::findOrFail($bookId);
        Gate::authorize('edit-book', $book);

        $cardIds = $request->card_ids;
        for ($i = 0; $i < count($cardIds); $i++) {
            Card::find($cardIds[$i])->fill([
                'card_order' => $i + 1
            ])->save();
        }
        $cards = Card::whereIn('card_id', $cardIds)->get();
        return response()->json(['cards' => $cards], 200);
    }

    /**
     * 指定問題の削除。
     *
     * @param integer $bookId
     * @param integer $cardId
     * @return \Illuminate\Http\Response
     */
    public function deleteCard($bookId, $cardId)
    {
        $book = Book::findOrFail($bookId);
        Gate::authorize('edit-book', $book);
        $card = Card::where([
            'card_id' => $cardId,
            'book_id' => $bookId
        ])->firstOrFail();
        Storage::deleteDirectory('public/images/cards/'.$card->card_id);
        $card->delete();
        return response()->json(['message' => 'Deleted.'], 200);
    }
}
