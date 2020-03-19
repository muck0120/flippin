<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $primaryKey = 'favorite_id';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'book_id'
    ];
}
