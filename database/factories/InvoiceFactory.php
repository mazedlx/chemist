<?php

use App\Category;
use Faker\Generator as Faker;

$factory->define(App\Invoice::class, function (Faker $faker) {
    return [
        'amount' => $faker->numberBetween(1, 10000),
        'category_id' => factory(Category::class)->create()->id,
        'date' => $faker->date,
    ];
});
