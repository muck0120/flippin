<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'book_title' => $faker->text(50),
        'book_desc' => $faker->text(200),
        'book_is_publish' => $faker->boolean,
    ];
});
