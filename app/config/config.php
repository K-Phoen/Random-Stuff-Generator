<?php

require_once __DIR__.'/../../vendor/autoload.php';

$app = new Silex\Application();
$app->register(new Silex\Provider\SerializerServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new KPhoen\Provider\FakerServiceProvider());

// Debug?
$app['debug'] = 'dev' === getenv('APPLICATION_ENV') || $_SERVER['REMOTE_ADDR'] === '10.0.2.2';

return $app;
