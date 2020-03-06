<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'book_is_publish' => 'boolean',
        'book_is_favorite' => 'boolean'
    ];
    protected $appends = [
        'book_username_created_by',
        'book_is_favorite',
        'book_favorite_count'
    ];

    const CREATED_AT = 'book_created_at';
    const UPDATED_AT = 'book_updated_at';

    public function getBookUsernameCreatedByAttribute()
    {
        return $this->user()->first()->user_name;
    }

    public function setBookUsernameCreatedByAttribute()
    {
        return false;
    }

    public function getBookIsFavoriteAttribute()
    {
        return $this->favorites()->pluck('user_id')->contains(Auth::id());
    }

    public function setBookIsFavoriteAttribute()
    {
        return false;
    }

    public function getBookFavoriteCountAttribute()
    {
        return $this->favorites()->count();
    }

    public function setBookFavoriteCountAttribute()
    {
        return false;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function favorites()
    {
        return $this->hasMany('App\Models\Favorite', 'book_id');
    }
}
