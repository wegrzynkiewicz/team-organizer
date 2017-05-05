<?php

declare(strict_types=1);

$nowDate = date('Y-m-d');
$filepath = DATA_PATH."/kp-{$nowDate}.json";

$persons = [];
if (is_readable($filepath)) {
    $json = file_get_contents($filepath);
    $persons = json_decode($json, true);
} else {
    touch($filepath);
    chmod($filepath, 0777);
}

$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';

if ($name and $description) {
    $persons[$name] = $description;
    $json = json_encode($persons, JSON_PRETTY_PRINT);
    file_put_contents($filepath, $json);
}

redirect('/', 303);

