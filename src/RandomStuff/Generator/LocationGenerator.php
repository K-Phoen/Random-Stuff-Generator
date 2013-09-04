<?php

namespace RandomStuff\Generator;

class LocationGenerator extends AbstractGenerator
{
    public function getOne($seed = null)
    {
        $seed = $this->generateSeed($seed);

        return array(
            'name'          => $this->faker->company,
            'description'   => $this->faker->paragraph,
            'address'       => array(
                'street'    => $this->faker->streetAddress,
                'city'      => $this->faker->city,
                'state'     => $this->faker->state,
                'zip'       => $this->faker->postcode,
                'coutry'    => $this->faker->country,
            ),
            'coordinates'   => array(
                'latitude'  => $this->faker->latitude,
                'longitude' => $this->faker->longitude,
            ),
            'seed'          => $seed,
        );
    }
}
