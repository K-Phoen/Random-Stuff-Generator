<?php

namespace RandomStuff\Generator;

use Faker\Generator as Faker;

abstract class AbstractGenerator implements GeneratorInterface
{
    protected $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    public function getCollection($size = 10)
    {
        $items = array();
        foreach (range(1, $size) as $i) {
            $items[] = $this->getOne();
        }
        return $items;
    }

    protected function generateSeed($seed)
    {
        $seed = $seed === null || $seed <= 0 ? mt_rand() : $seed;
        $this->faker->seed($seed);

        return $seed;
    }
}
