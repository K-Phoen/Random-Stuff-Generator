<?php

namespace RandomStuff\Faker;

class Generator extends \Faker\Generator
{
    /**
     * @return Callable
     */
    public function getFormatter($formatter)
    {
        try {
            return parent::getFormatter($formatter);
        } catch (\InvalidArgumentException $e) {
            return function() {
                return null;
            };
        }
    }
}
