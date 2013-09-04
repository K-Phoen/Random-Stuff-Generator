<?php

namespace RandomStuff\Faker;

use RandomStuff\Faker\Generator;

class Factory extends \Faker\Factory
{
    public static function create($locale = self::DEFAULT_LOCALE)
    {
        $generator = new Generator();
        foreach (static::$defaultProviders as $provider) {
            $providerClassName = self::getProviderClassname($provider, $locale);
            $generator->addProvider(new $providerClassName($generator));
        }

        return $generator;
    }
}
