<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'user_name',
        'user_mail',
        'user_password',
        'user_api_token'
    ];
    protected $hidden = [
        'user_password',
        'user_api_token'
    ];

    const CREATED_AT = 'user_created_at';
    const UPDATED_AT = 'user_updated_at';

    public function getAuthIdentifier()
    {
        return 'user_id';
    }
}
