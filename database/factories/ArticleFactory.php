<?php

use Faker\Generator as Faker;

$factory->define(App\Articles::class, function (Faker $faker) {
    return [
        'title' => $faker->text(50),
        'body' => $faker->sentence(5)
    ];
});
