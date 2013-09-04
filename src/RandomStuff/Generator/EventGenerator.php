<?php

namespace RandomStuff\Generator;

use Faker\Generator as Faker;

class EventGenerator extends AbstractGenerator
{
    protected $userGenerator, $locationGenerator;

    public function __construct(Faker $faker, UserGenerator $userGenerator, LocationGenerator $locationGenerator)
    {
        parent::__construct($faker);

        $this->userGenerator = $userGenerator;
        $this->locationGenerator = $locationGenerator;
    }

    public function getOne($seed = null, array $overridenValues = array())
    {
        $seed = $this->generateSeed($seed);

        $user = array(
            'title'         => $this->faker->sentence(4),
            'description'   => $this->faker->paragraph(),
            'start_date'    => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s'),
            'end_date'      => $this->faker->dateTimeBetween('+1 hour', '+1 month')->format('Y-m-d H:i:s'),
            'timezone'      => $this->faker->timezone,
            'venue'         => $this->locationGenerator->getOne($seed),
            'owner'         => $this->userGenerator->getOne($seed),
            'seed'          => $seed,
        );

        return $this->overrideValues($user, $overridenValues);
    }
}
