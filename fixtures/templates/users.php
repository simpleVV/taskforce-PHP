<?php

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'email' => $faker->email,
    'name' => $faker->company,
    'password' => $faker->password,
    'city_id' => $faker->numberBetween(1, 30),
    'role_id' => $faker->numberBetween(1, 2)
];
