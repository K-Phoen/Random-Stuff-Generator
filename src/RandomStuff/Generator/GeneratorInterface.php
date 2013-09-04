<?php

namespace RandomStuff\Generator;

interface GeneratorInterface
{
    public function getOne($seed = null, array $overridenValues = array());

    public function getCollection($size = 10, array $overridenValues = array());
}
