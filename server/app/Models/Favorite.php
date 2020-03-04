<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $primaryKey = 'favorite_id';
    protected $fillable = [
        'user_id',
        'book_id'
    ];

    const CREATED_AT = null;
    const UPDATED_AT = null;
}
