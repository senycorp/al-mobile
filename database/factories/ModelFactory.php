<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


/**
 * Car Factory
 */
$factory->define(App\Car::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->randomElement([
            'Opel Astra',
            'Opel Insignia',
            'Opel Adam',
            'VW Golf',
            'VW Passat',
            'VW Bora',
            'VW Pheaton',
            'Mercedes E-Klasse',
            'Mercedes A-Klasse',
            'Mercedes C-Klasse',
            'Mercedes B-Klasse',
            'Renault Clio'
        ]),
        'chassis_number' => $faker->uuid,
        'purchase_date' => $faker->dateTimeBetween('-1 year', '-1 month'),
        'purchase_price' => $faker->numberBetween(1000, 20000)
    ];
});

/**
 * Car State: Selled
 */
$factory->state(App\Car::class, 'selled', function (Faker\Generator $faker) {
    return [
        'sale_date' => $faker->dateTimeBetween('-1 month'),
        'sale_price' => $faker->numberBetween(10000, 35000)
    ];
});

/**
 * Invoice Factory
 */
$factory->define(App\Invoice::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->randomElement([
            'Tank',
            'Reparatur',
            'PC',
            'Gutachten',
            'Elektronik',
            'Lakierung'
        ]),
        'price' => $faker->numberBetween(1, 2000),
        'date' => $faker->dateTimeBetween('-1 year', '-1 month'),
        'description' => $faker->numberBetween(1000, 20000)
    ];
});