<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', 'UserController@login');
Route::post('/users/profile', 'UserController@createUser');

Route::middleware(['auth:api'])->group(function () {
    Route::get('/logout', 'UserController@logout');
    Route::get('/users/profile', 'UserController@getProfile');
    Route::put('/users/profile', 'UserController@updateProfile');
});

Route::get('/unauthorized', function () {
    return response()->json(['message' => 'Unauthorized.'], 401);
});
