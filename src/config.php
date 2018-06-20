<?php

declare(strict_types=1);

define('DEBUG', true);
define('ROOT_PATH', realpath(__DIR__.'/..'));
define('VAR_PATH', ROOT_PATH.'/var');
define('SOURCE_PATH', ROOT_PATH.'/src');
define('RESOURCES_PATH', ROOT_PATH.'/resources');
define('TEMPLATES_PATH', ROOT_PATH.'/templates');

ini_set('session.save_path', VAR_PATH.'/sessions');

return [
    'projectName' => 'RedCart Team Organizer',
    'employees' => require_once RESOURCES_PATH.'/employees.php',
    'culinary' => [
        'restaurants' => [
            'chinczyk' => 'Chińczyk',
            'nalesnikarnia' => 'Naleśnikarnia',
            'pizzeria-italia' => 'Pizzeria Italia',
            'da-grasso' => 'Pizzeria Da Grasso',
            'mamuski' => 'Restauracja Mamuśki',
            'kfc' => 'Kentucky Fried Chicken',
        ],
        'images' => [
            'Gofry' => '/assets/culinary/gofry.jpg',
            'Naleśniki' => '/assets/culinary/nalesniki.jpg',
            'Pierogi' => '/assets/culinary/pierogi.jpg',
            'Placki ziemniaczane' => '/assets/culinary/placki-ziemniaczane.jpg',
            'Chińczyk 1' => '/assets/culinary/chinczyk1.jpg',
            'Chińczyk 2' => '/assets/culinary/chinczyk2.jpg',
            'Italia 1' => '/assets/culinary/italia3.jpg',
            'Italia 2' => '/assets/culinary/italia4.jpg',
            'Mamuśki 1' => '/assets/culinary/mamuski1.jpg',
            'Mamuśki 2' => '/assets/culinary/mamuski2.jpg',
            'Da Grasso 1' => '/assets/culinary/da-grasso1.jpg',
            'Da Grasso 2' => '/assets/culinary/da-grasso2.jpg',
        ],
    ],
];
