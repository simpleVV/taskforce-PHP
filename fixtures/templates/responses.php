<?php

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'comment' => $faker->sentences($nb = 3, $asText = true),
    'price' => $faker->numberBetween(1000, 3000),
    'is_approved' => "",
    'task_id' => $faker->numberBetween(1, 10),
    'user_id' => $faker->numberBetween(1, 3),
];
