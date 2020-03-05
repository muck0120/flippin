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

Route::get('/books/{bookGroup}', 'BookController@getBooks');
Route::get('/books/{bookId}', 'BookController@getBook');

Route::middleware(['auth:api'])->group(function () {
    Route::get('/logout', 'UserController@logout');
    Route::get('/users/profile', 'UserController@getProfile');
    Route::put('/users/profile', 'UserController@updateProfile');

    Route::post('/books/book', 'BookController@createBook');
    Route::put('/books/{bookId}', 'BookController@updateBook');
    Route::delete('/books/{bookId}', 'BookController@deleteBook');

    Route::post('/favorite/{bookId}', 'FavoriteController@createFavorite');
    Route::delete('/favorite/{bookId}', 'FavoriteController@deleteFavorite');
});

Route::get('/unauthorized', function () {
    return response()->json(['message' => 'Unauthorized.'], 401);
});
Route::get('/notfound', function () {
    return response()->json(['message' => 'Not found.'], 404);
});
Route::fallback(function () {
    return response()->json(['message' => 'Not found.'], 404);
});
