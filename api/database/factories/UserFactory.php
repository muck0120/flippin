<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'user_name' => $faker->userName,
        'user_mail' => $faker->safeEmail,
        'user_password' => Hash::make('password')
    ];
});
