<?php

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'email' => $faker->email,
    'phone' => substr($faker->e164PhoneNumber, 1, 11),
    'telegram' => substr($faker->e164PhoneNumber, 1, 11),
    'user_id' => $faker->numberBetween(1, 5),
];
