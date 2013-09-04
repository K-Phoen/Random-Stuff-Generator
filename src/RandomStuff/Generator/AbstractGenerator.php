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

    public function getCollection($size = 10, array $overridenValues = array())
    {
        $items = array();
        foreach (range(1, $size) as $i) {
            $items[] = $this->getOne(null, $overridenValues);
        }
        return $items;
    }

    protected function generateSeed($seed)
    {
        $seed = $seed === null || $seed <= 0 ? mt_rand() : $seed;
        $this->faker->seed($seed);

        return $seed;
    }

    protected function overrideValues(array $baseValues, array $rawOverridenValues)
    {
        // unflatten the values
        $overridenValues = array();
        foreach ($rawOverridenValues as $path => $value) {
            $this->unflatten($overridenValues, $path, $value);
        }

        // filter invalid values that may have been given
        $overridenValues = array_intersect_key($overridenValues, $baseValues);

        // proceed to the merge
        $values = $this->mergeArrays($baseValues, $overridenValues);

        // never override the 'seed' key
        return array_merge($values, array('seed' => $baseValues['seed']));
    }

    /**
     * $ar1 = array('color' => array('favorite' => 'red'), 5);
     * $ar2 = array(10, 'color' => array('favorite' => 'green', 'blue'));
     * With array_merge_recursive, we have: array(color => array('favorite' => ['red', 'green']))
     * With this method, we have: array(color => array('green'))
     */
    protected function mergeArrays($config1, $config2)
    {
        if (!is_array($config1) || !is_array($config2)) {
            return $config2;
        }

        foreach ($config2 as $key => $value) {
            if (!isset($config1[$key])) {
                $config1[$key] = $value;
                continue;
            }

            $config1[$key] = $this->mergeArrays($config1[$key], $value);
        }

        return $config1;
    }

    protected function unflatten(array &$arr, $path, $val, $separator = ':')
    {
        $loc = &$arr;
        foreach (explode($separator, $path) as $step) {
            $loc = &$loc[$step];
        }

        return $loc = $val;
    }
}
