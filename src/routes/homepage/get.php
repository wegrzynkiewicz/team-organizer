<?php

declare(strict_types=1);

$nowDate = date('Y-m-d');
$filepath = DATA_PATH."/kp-{$nowDate}.json";

$persons = [];
if (is_readable($filepath)) {
    $json = file_get_contents($filepath);
    $persons = json_decode($json, true);
    ksort($persons);
}

echo render(__DIR__.'/view.html.php', [
    'persons' => $persons,
]);

