<?php

namespace RandomStuff\Tests\Functional;

use Silex\WebTestCase as BaseWebTestCase;

abstract class WebTestCase extends BaseWebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../../../app/app.php';

        $app['debug'] = true;
        $app['exception_handler']->disable();

        return $app;
    }
}
