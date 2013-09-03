<?php

namespace RandomStuff\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiController implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/users', array($this, 'getUsersAction'));
        $controllers->get('/users/single', array($this, 'getUserAction'));

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

    public function getUserAction(Application $app, Request $request)
    {
        $seed = (int) $request->query->get('seed');
        $user = $app['user.generator']->getOne($seed);

        return array(
            'results' => $user
        );
    }

    public function getUsersAction(Application $app, Request $request)
    {
        $size = (int) $request->query->get('size', 10);
        $users = $app['user.generator']->getCollection($size);

        return array(
            'results' => $users
        );
    }
}
