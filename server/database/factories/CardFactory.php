<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Card;
use App\Models\CardChoice;
use Faker\Generator as Faker;

$factory->define(Card::class, function (Faker $faker) {
    return [
        'card_question' => $faker->text(2000),
        'card_explanation' =>  $faker->text(2000)
    ];
});

$factory->afterCreating(Card::class, function ($card) {
    factory(CardChoice::class)->create([
        'card_id' => $card->card_id,
        'card_choice_is_correct' => true
    ]);
    factory(CardChoice::class, 3)->create([
        'card_id' => $card->card_id
    ]);
});
