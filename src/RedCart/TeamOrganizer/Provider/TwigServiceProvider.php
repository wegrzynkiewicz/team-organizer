<?php

declare(strict_types=1);

namespace RedCart\TeamOrganizer\Provider;

use Redcart\TeamOrganizer\Foundation\Container;
use RedCart\TeamOrganizer\Foundation\ServiceProviderInterface;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Filter;
use Twig_Loader_Filesystem;

class TwigServiceProvider implements ServiceProviderInterface
{
    /** @var array */
    private $container = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getServiceName()
    {
        return 'twig';
    }

    public function provide()
    {
        $loader = new Twig_Loader_Filesystem(TEMPLATES_PATH);
        $options = [];

        if (!DEBUG) {
            $options['cache'] = VAR_PATH.'/twig-cache';
        }

        $twig = new Twig_Environment($loader, $options);
        $twig->addGlobal('container', $this->container);
        $twig->addFilter(new Twig_Filter('currency', function ($value) {
            return number_format(floatval($value), 2, '.', ' ').' zł';
        }));

        if (DEBUG) {
            $twig->addExtension(new Twig_Extension_Debug());
        }

        return $twig;
    }
}