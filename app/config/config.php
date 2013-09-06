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
$app->register(new Knp\Provider\ConsoleServiceProvider(), array(
    'console.name'              => 'RandomStuffGenerator',
    'console.version'           => '1.0.0',
    'console.project_directory' => __DIR__.'/..'
));

// Debug?
$app['debug'] = 'dev' === getenv('APPLICATION_ENV') || (!empty($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] === '10.0.2.2');

if ($app['debug']) {
    $app->register(new Silex\Provider\WebProfilerServiceProvider(), array(
        'profiler.cache_dir'    => __DIR__.'/../cache/profiler',
        'profiler.mount_prefix' => '/_profiler', // this is the default
    ));
}

// parameters
$app['flickr.api_key'] = 'your_api_key';
$app['flickr.api_secret'] = 'your_secret';

$app['avatar.directory'] = __DIR__ . '/../../web/avatars';
$app['avatar.thumb.directory'] = __DIR__ . '/../../web/avatars/thumbs';

return $app;
