<?php

$app = require_once __DIR__ . '/config/config.php';

/*************
 * Services
 ************/

$app['format.negociator'] = $app->share(function() {
    return new \Negotiation\FormatNegotiator();
});

$app['response.formatter'] = $app->share(function($app) {
    return new \RandomStuff\Response\ResponseFormatter($app['serializer'], $app['format.negociator']);
});

// generators
$app['location.generator'] = $app->share(function($app) {
    return new \RandomStuff\Generator\LocationGenerator($app['faker']);
});

$app['user.generator'] = $app->share(function($app) {
    return new \RandomStuff\Generator\UserGenerator($app['faker']);
});

$app['event.generator'] = $app->share(function($app) {
    return new \RandomStuff\Generator\EventGenerator($app['faker'], $app['user.generator'], $app['location.generator']);
});

// controllers
$app['frontend.controller'] = $app->share(function() {
    return new \RandomStuff\Controller\FrontendController();
});


/*************
 * Roultes
 ************/

$app->get('/', 'frontend.controller:indexAction');
$app->mount('/api', new \RandomStuff\Controller\ApiController('users', 'user.generator'));
$app->mount('/api', new \RandomStuff\Controller\ApiController('locations', 'location.generator'));
$app->mount('/api', new \RandomStuff\Controller\ApiController('events', 'event.generator'));

return $app;
