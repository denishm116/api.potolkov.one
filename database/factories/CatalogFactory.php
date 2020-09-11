<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Models\Catalog::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->name,
        'slug' => $faker->unique()->slug(2),
        'parent_id' => null,
    ];
});
