<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/config.php';
require __DIR__.'/functions.php';

$method = strtolower($_SERVER['REQUEST_METHOD'] ?? 'GET');

require __DIR__."/routes/homepage/{$method}.php";
