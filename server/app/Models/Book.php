<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $primaryKey = 'book_id';
    protected $fillable = [
        'user_id',
        'book_title',
        'book_desc',
        'book_is_publish'
    ];
    protected $casts = [
        'book_is_publish' => 'boolean'
    ];

    const CREATED_AT = 'book_created_at';
    const UPDATED_AT = 'book_updated_at';
}
