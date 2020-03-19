<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CardChoice;
use Faker\Generator as Faker;

$factory->define(CardChoice::class, function (Faker $faker) {
    return [
        'card_choice_text' => $faker->text(200),
        'card_choice_is_correct' => false
    ];
});
