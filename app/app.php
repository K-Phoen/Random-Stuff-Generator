<?php

$app = require __DIR__ . '/config/config.php';

/*************
 * Services
 ************/

$app['format.negociator'] = $app->share(function() {
    return new \Negotiation\FormatNegotiator();
});

$app['response.formatter'] = $app->share(function($app) {
    return new \RandomStuff\Response\ResponseFormatter($app['serializer'], $app['format.negociator']);
});

// gaufrette related
$app['avatar.filesystem'] = $app->share(function($app) {
    $host = $app['request']->getSchemeAndHttpHost();
    $adapter = new \Gaufrette\Adapter\Local($app['avatar.directory'], true);
    $resolver = new \GaufretteExtras\Resolver\PrefixResolver($host.'/avatars');

    return new \GaufretteExtras\ResolvableFilesystem(new \GaufretteExtras\Adapter\ResolvableAdapter($adapter, $resolver));
});
$app['avatar.thumb.filesystem'] = $app->share(function($app) {
    $host = $app['request']->getSchemeAndHttpHost();
    $adapter = new \Gaufrette\Adapter\Local($app['avatar.thumb.directory'], true);
    $resolver = new \GaufretteExtras\Resolver\PrefixResolver($host.'/avatars/thumbs');

    return new \GaufretteExtras\ResolvableFilesystem(new \GaufretteExtras\Adapter\ResolvableAdapter($adapter, $resolver));
});

// flickr related
$app['flickr.metadata'] = $app->share(function($app) {
    $metadata = new \Rezzza\Flickr\Metadata($app['flickr.api_key'], $app['flickr.api_secret']);

    return $metadata;
});

$app['flickr.http_adapter'] = $app->share(function() {
    return new \Rezzza\Flickr\Http\GuzzleAdapter();
});

$app['flickr'] = $app->share(function($app) {
    return new \Rezzza\Flickr\ApiFactory($app['flickr.metadata'], $app['flickr.http_adapter']);
});

// generators
$app['location.generator'] = $app->share(function($app) {
    return new \RandomStuff\Generator\LocationGenerator($app['faker']);
});

$app['user.generator'] = $app->share(function($app) {
    return new \RandomStuff\Generator\UserGenerator($app['faker'], $app['avatar.directory'], $app['avatar.thumb.directory'], $app['avatar.filesystem'], $app['avatar.thumb.filesystem']);
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
