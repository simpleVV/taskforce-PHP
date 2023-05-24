<?php

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'email' => $faker->unique()->email,
    'name' => $faker->name,
    'password' => $faker->password,
    'city_id' => $faker->numberBetween(1, 30),
    'role_id' => $faker->numberBetween(1, 2)
];
