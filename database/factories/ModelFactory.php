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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => app('hash')->make('123')
    ];
});

$factory->define(App\Schedule::class, function (Faker\Generator $faker) {
    return [
        'date' => $faker->dateTime(),
        'users_id' => \Illuminate\Support\Facades\DB::table('users')->pluck('id')->random()
    ];
});
