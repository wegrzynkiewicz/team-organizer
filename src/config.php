<?php

declare(strict_types=1);

define('DEBUG', true);
define('ROOT_PATH', realpath(__DIR__ . '/..'));
define('VAR_PATH', ROOT_PATH . '/var');
define('SOURCE_PATH', ROOT_PATH . '/src');
define('RESOURCES_PATH', ROOT_PATH . '/resources');
define('TEMPLATES_PATH', ROOT_PATH . '/templates');

ini_set('session.save_path', VAR_PATH . '/sessions');

return [
    'projectName' => 'RedCart Team Organizer',
    'employees' => require_once RESOURCES_PATH.'/employees.php',
    'culinary' => [
        'restaurants' => [
            'Chińczyk',
            'Naleśnikarnia',
            'Pizzeria Italia',
        ],
        'images' => [
            'Gofry' => '/culinary/gofry.jpg',
            'Naleśniki' => '/culinary/nalesniki.jpg',
            'Pierogi' => '/culinary/pierogi.jpg',
            'Placki ziemniaczane' => '/culinary/placki-ziemniaczane.jpg',
        ],
    ],
];