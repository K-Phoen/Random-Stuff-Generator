<?php

namespace RandomStuff\Controller;

use Silex\Application;

class FrontendController
{
    public function indexAction(Application $app)
    {
        return $app['twig']->render('index.html.twig');
    }
}
