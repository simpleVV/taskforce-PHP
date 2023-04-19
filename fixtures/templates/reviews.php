<?php

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'description' => $faker->sentences($nb = 3, $asText = true),
    'rate' => $faker->numberBetween(1, 5),
    'user_id' => $faker->numberBetween(1, 5),
    'client_id' => $faker->numberBetween(1, 3),
];
