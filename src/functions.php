<?php

declare(strict_types=1);

function render(string $path, array $_PARAMETERS = []): string
{
    extract($_PARAMETERS);

    ob_start();
    require $path;
    $rendered = ob_get_clean();

    return $rendered;
}

function escape(string $string): string
{
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url, int $code): void
{
    http_response_code($code);
    header ("Location: {$url}");

    exit;
}