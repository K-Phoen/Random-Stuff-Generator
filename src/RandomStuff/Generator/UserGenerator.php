<?php

namespace RandomStuff\Generator;

use Faker\Generator as Faker;

class UserGenerator
{
    protected $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    public function getOne($seed = null)
    {
        $seed = $seed === null || $seed <= 0 ? $this->generateSeed() : $seed;

        $this->faker->seed($seed);

        return array(
            //'gender'    => '',
            'name'      => array(
                //'title' => '',
                'first' => $this->faker->firstName,
                'last'  => $this->faker->lastName,
            ),
            'location'  => array(
                'street'    => $this->faker->streetAddress,
                'city'      => $this->faker->city,
                'state'     => $this->faker->state,
                'zip'       => $this->faker->postcode,
            ),
            'birthday'  => $this->faker->date,
            'email'     => $this->faker->email,
            //'password'  => '',
            'md5_hash'  => $this->faker->md5,
            'sha1_hash' => $this->faker->sha1,
            'phone'     => $this->faker->phoneNumber,
            'seed'      => $seed,
        );
    }

    public function getCollection($size = 10)
    {
        $users = array();
        foreach (range(1, $size) as $i) {
            $users[] = $this->getOne();
        }
        return $users;
    }

    protected function generateSeed()
    {
        return mt_rand();
    }
}
