<?php

namespace RandomStuff\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiController implements ControllerProviderInterface
{
    protected $baseUrl, $generatorService;

    public function __construct($baseUrl, $generatorService)
    {
        $this->baseUrl = $baseUrl;
        $this->generatorService = $generatorService;
    }

    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get(sprintf('/%s', $this->baseUrl), array($this, 'getCollectionAction'));
        $controllers->get(sprintf('/%s/single', $this->baseUrl), array($this, 'getSingleAction'));

        // will automagically generate a response from the data returned by
        // the controllers
        $app->on(KernelEvents::VIEW, function(GetResponseForControllerResultEvent $event) use ($app) {
            // we expect data given as an array
            if (!is_array($event->getControllerResult())) {
                return;
            }

            $response = $app['response.formatter']->format($event->getRequest(), $event->getControllerResult());
            $event->setResponse($response);
        });

        return $controllers;
    }

    public function getSingleAction(Application $app, Request $request)
    {
        $seed = (int) $request->query->get('seed');
        $item = $app[$this->generatorService]->getOne($seed);

        return array(
            'results' => $item
        );
    }

    public function getCollectionAction(Application $app, Request $request)
    {
        $size = (int) $request->query->get('size', 10);
        $items = $app[$this->generatorService]->getCollection($size);

        return array(
            'results' => $items
        );
    }
}
