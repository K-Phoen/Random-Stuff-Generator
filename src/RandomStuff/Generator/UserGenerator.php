<?php

namespace RandomStuff\Generator;

use Faker\Generator as Faker;
use GaufretteExtras\ResolvableFilesystem;
use Symfony\Component\Finder\Finder;

class UserGenerator extends AbstractGenerator
{
    protected $avatars_directory, $avatars_thumbs_directory;
    protected $avatarsFs, $avatarsThumbsFs;

    public function __construct(Faker $faker, $avatars_directory, $avatars_thumbs_directory, ResolvableFilesystem $avatarsFs, ResolvableFilesystem $avatarsThumbsFs)
    {
        parent::__construct($faker);

        $this->avatars_directory = $avatars_directory;
        $this->avatars_thumbs_directory = $avatars_thumbs_directory;

        $this->avatarsFs = $avatarsFs;
        $this->avatarsThumbsFs = $avatarsThumbsFs;
    }

    public function getOne($seed = null, array $overridenValues = array())
    {
        $seed = $this->generateSeed($seed);
        $avatar = $this->getRandomFile($this->avatars_directory);

        $user = array(
            //'gender'    => '',
            'name'          => array(
                //'title' => '',
                'first' => $this->faker->firstName,
                'last'  => $this->faker->lastName,
            ),
            'location'      => array(
                'street'    => $this->faker->streetAddress,
                'city'      => $this->faker->city,
                'state'     => $this->faker->state,
                'zip'       => $this->faker->postcode,
            ),
            'picture'       => $avatar === null ? null : $this->avatarsFs->resolve($avatar),
            'picture_thumb' => $avatar === null ? null : $this->avatarsThumbsFs->resolve($avatar),
            'birthday'      => $this->faker->date,
            'email'         => $this->faker->email,
            'password'      => $this->getRandomPassword(),
            'md5_hash'      => $this->faker->md5,
            'sha1_hash'     => $this->faker->sha1,
            'phone'         => $this->faker->phoneNumber,
            'seed'          => $seed,
        );

        return $this->overrideValues($user, $overridenValues);
    }

    protected function getRandomFile($directory)
    {
        $finder = new Finder();
        $files = $finder->files()->depth('0')->in($directory);
        $files = array_keys(iterator_to_array($files));

        if (!$files) {
            return null;
        }

        return basename($this->faker->randomElement($files));
    }

    protected function getRandomPassword()
    {
        $length = $this->faker->randomNumber(5, 12);
        $password = '';

        while ($length--) {
            if ($this->faker->boolean) {
                $password .= $this->faker->randomLetter;
            } else {
                $password .= $this->faker->randomDigit;
            }
        }

        return $password;
    }
}
