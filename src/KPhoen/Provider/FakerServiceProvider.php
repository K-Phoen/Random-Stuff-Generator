<?php

namespace KPhoen\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;

use RandomStuff\Faker\Factory;

/**
 * A Faker service provider for Silex
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class FakerServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
    }

    public function boot(Application $app)
    {
        $app['faker'] = $app->share(function($app) {
            $language = $this->getBestLanguage($app['request'], $app['locale']);

            return Factory::create($language);
        });
    }

    protected function getBestLanguage(Request $request, $default = null)
    {
        foreach ($request->getLanguages() as $language) {
            if (strpos($language, '_') !== false) {
                return $language;
            }
        }

        return $default;
    }
}
