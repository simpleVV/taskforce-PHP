<?php

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'title' => $faker->word(),
    'description' => $faker->sentences($nb = 3, $asText = true),
    'location' => $faker->address,
    'price' => $faker->numberBetween(1000, 3000),
    'dt_expire' => $faker->dateTimeBetween('-1 years', 'now', 'Asia/Vladivostok'),
    'category_id' => $faker->numberBetween(1, 8),
    'client_id' => $faker->numberBetween(1, 3),
    'performer_id' => $faker->numberBetween(3, 5),
    'status_id' => $faker->numberBetween(1, 5),
];
