<?php

namespace App\Http\Requests;

class CardRequest extends ApiRequest
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
            'card_question' => ['required', 'max:2000'],
            'card_question_image' => ['image', 'file'],
            'card_choices' => [
                'required', 'array', 'min:2', function ($attr, $values, $fail) {
                    $collection = array_map(function ($value) {
                        if (isset($value['card_choice_is_correct'])) {
                            return (integer)$value['card_choice_is_correct'];
                        } else {
                            return 'none';
                        }
                    }, $values);
                    $count = isset(array_count_values($collection)[1]) ?
                        array_count_values($collection)[1] : 0;
                    if ($count !== 1) $fail($attr.'.bad_correct_choices');
                }
            ],
            'card_choices.*.card_choice_text' => ['required', 'max:200'],
            'card_choices.*.card_choice_is_correct' => ['required', 'boolean'],
            'card_explanation' => ['max:2000'],
            'card_explanation_image' => ['image', 'file']
        ];
    }
}
