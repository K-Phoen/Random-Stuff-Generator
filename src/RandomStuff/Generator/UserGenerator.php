<?php

namespace RandomStuff\Generator;

class UserGenerator extends AbstractGenerator
{
    public function getOne($seed = null, array $overridenValues = array())
    {
        $seed = $this->generateSeed($seed);

        $user = array(
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

        return $this->overrideValues($user, $overridenValues);
    }
}
