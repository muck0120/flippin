<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardChoice extends Model
{
    protected $primaryKey = 'card_id';
    public $timestamps = false;
    protected $fillable = [
        'card_id',
        'card_choice_text',
        'card_choice_is_correct'
    ];
    protected $casts = [
        'card_id' => 'integer',
        'card_choice_is_correct' => 'boolean'
    ];
}
