<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\Post::class, function (Faker\Generator $faker) {
    return [
    	'title'=> $faker->sentence(5),
    	'image'=> $faker->sentence(5),
    	'excerpt'=> $faker->text(),
    	'content'=> $faker->text(),
    	'category'=> $faker->sentence(5),
    	'tags'=> $faker->sentence(5),
    	'status'=> $faker->sentence(5),
    ];
});