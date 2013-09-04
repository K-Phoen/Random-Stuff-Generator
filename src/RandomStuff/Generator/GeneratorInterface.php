<?php

namespace RandomStuff\Generator;

interface GeneratorInterface
{
    public function getOne($seed = null);

    public function getCollection($size = 10);
}
