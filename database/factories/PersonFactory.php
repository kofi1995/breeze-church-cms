<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Person;
use Faker\Generator as Faker;

$factory->define(Person::class, function (Faker $faker) {
    $created_updated_date = $faker->dateTimeBetween('-2 years', 'now');
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email_address' => $faker->email,
        'status' => (bool)random_int(0, 1) ? 'active' : 'archived',
        'created_at' => $created_updated_date,
        'updated_at' => $created_updated_date
    ];
});
