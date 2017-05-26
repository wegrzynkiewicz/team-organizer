<?php

declare(strict_types=1);

use RedCart\TeamOrganizer\Foundation\Container;
use RedCart\TeamOrganizer\Provider\TwigServiceProvider;
use RedCart\TeamOrganizer\Routing\AnnotationRouter;

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/functions.php';
$config = require __DIR__.'/config.php';

session_start();

$container = new Container();
$container->set('config', $config);
$container->registerProvider(new TwigServiceProvider($container));

$router = new AnnotationRouter($container, VAR_PATH.'/routing.php');
$router->executeControllerByRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
