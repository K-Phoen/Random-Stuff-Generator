<?php

require_once __DIR__.'/../../vendor/autoload.php';

$app = new Silex\Application();
$app->register(new Silex\Provider\SerializerServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new KPhoen\Provider\FakerServiceProvider('\RandomStuff\Faker\Factory'));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// Debug?
$app['debug'] = 'dev' === getenv('APPLICATION_ENV') || $_SERVER['REMOTE_ADDR'] === '10.0.2.2';

if ($app['debug']) {
    $app->register(new Silex\Provider\WebProfilerServiceProvider(), array(
        'profiler.cache_dir'    => __DIR__.'/../cache/profiler',
        'profiler.mount_prefix' => '/_profiler', // this is the default
    ));
}

return $app;
