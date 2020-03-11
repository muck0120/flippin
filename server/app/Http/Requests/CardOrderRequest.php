<?php

namespace App\Http\Requests;

use App\Models\Card;

class CardOrderRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'card_ids' => [
                'required',
                'array',
                function ($attr, $values, $fail) {
                    $storedCardIds = Card::where('book_id', $this->bookId)->pluck('card_id');
                    $requestCardIds = collect($values)->map(function ($value) {
                        return (int)$value;
                    });
                    $diff1 = $storedCardIds->diff($requestCardIds)->all();
                    $diff2 = $requestCardIds->diff($storedCardIds)->all();
                    if (count($diff1) !== 0 || count($diff2) !== 0) {
                        $fail($attr.'.not_match_card_ids');
                    }
                }
            ]
        ];
    }
}
