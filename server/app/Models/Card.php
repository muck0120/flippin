<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Card extends Model
{
    protected $primaryKey = 'card_id';
    protected $fillable = [
        'book_id',
        'card_order',
        'card_question',
        'card_question_image',
        'card_explanation',
        'card_explanation_image'
    ];
    protected $casts = [
        'book_id' => 'integer',
        'card_order' => 'integer'
    ];
    protected $appends = [
        'card_choices'
    ];

    const CREATED_AT = 'card_created_at';
    const UPDATED_AT = 'card_updated_at';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('card_order');
        });
    }

    public function getCardChoicesAttribute()
    {
        if (!empty($this->choices())) {
            return $this->choices()->get();
        }
    }

    public function setCardChoicesAttribute()
    {
        return false;
    }

    public function choices() {
        return $this->hasMany('App\Models\CardChoice', 'card_id');
    }

    /**
     * 質問文画像の更新。
     *
     * @param \App\Http\Requests\CardRequest
     * @return void
     */
    public function updateQuestionImage($request)
    {
        $requestHasQuestionImage = $request->hasFile('card_question_image');
        $databaseHasQuestionImage = isset($this->card_question_image);

        if (!$requestHasQuestionImage && $databaseHasQuestionImage) {
            Storage::delete('public/images/cards/'.$this->card_id.'/'.$this->card_question_image);
            $this->fill(['card_question_image' => null])->save();
        }
        if ($requestHasQuestionImage && !$databaseHasQuestionImage) {
            $path = $request->file('card_question_image')
                ->store('public/images/cards/'.$this->card_id);
            $this->fill(['card_question_image' => basename($path)])->save();
        }
        if ($requestHasQuestionImage && $databaseHasQuestionImage) {
            Storage::delete('public/images/cards/'.$this->card_id.'/'.$this->card_question_image);
            $path = $request->file('card_question_image')
                ->store('public/images/cards/'.$this->card_id);
            $this->fill(['card_question_image' => basename($path)])->save();
        }
    }

    /**
     * 解説文画像の更新。
     *
     * @param \App\Http\Requests\CardRequest
     * @return void
     */
    public function updateExplanationImage($request)
    {
        $requestHasExplanationImage = $request->hasFile('card_explanation_image');
        $databaseHasExplanationImage = isset($this->card_explanation_image);

        if (!$requestHasExplanationImage && $databaseHasExplanationImage) {
            Storage::delete('public/images/cards/'.$this->card_id.'/'.$this->card_explanation_image);
            $this->fill(['card_explanation_image' => null])->save();
        }
        if ($requestHasExplanationImage && !$databaseHasExplanationImage) {
            $path = $request->file('card_explanation_image')
                ->store('public/images/cards/'.$this->card_id);
            $this->fill(['card_explanation_image' => basename($path)])->save();
        }
        if ($requestHasExplanationImage && $databaseHasExplanationImage) {
            Storage::delete('public/images/cards/'.$this->card_id.'/'.$this->card_explanation_image);
            $path = $request->file('card_explanation_image')
                ->store('public/images/cards/'.$this->card_id);
            $this->fill(['card_explanation_image' => basename($path)])->save();
        }
    }
}
