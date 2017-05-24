<?php

declare(strict_types=1);

/**
 * Zapisuje zadane dane do pliku w formie łatwego do odczytu pliku PHP
 *
 * @param $file string Ścieżka absolutna do pliku, który zostanie zapisany
 * @param $data mixed Dane, które zostaną wyeksportowane do pliku
 * @return void
 */
function exportVariable2PHPFile(array $data, $file)
{
    makeFile($file, 0777);
    $date = date('Y-m-d H:i:s');
    $export = var_export($data, true);
    $content = "<?php\n\n/** @generated {$date} */\n\nreturn {$export};";
    file_put_contents($file, $content);
}
/**
 * Wyszukuje plików na dysku
 *
 * @param $pattern string Wyrażenie, tak samo jak w glob
 * @param int $flags Flagi, tak samo jak w przypadku glob
 * @return array Zwraca płaską tablicę z ścieżkami do znalezionych plików
 */
function globRecursive($pattern, $flags = 0)
{
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
        $files = array_merge($files, globRecursive($dir . '/' . basename($pattern), $flags));
    }
    return $files;
}
/**
 * Tworzy rekursywnie katalogi w ścieżce
 *
 * @param $path string Ścieżka absolutna do katalogów które chcesz stworzyć
 * @param int $mode Prawa dostępu do katalogu
 * @return void
 */
function makeDirsRecursive($path, $mode = 0777)
{
    $path = rtrim($path, '/');
    if (empty($path)) {
        return;
    }
    $dirs = explode('/', $path);
    $dir = '';
    foreach ($dirs as $part) {
        $dir .= $part . '/';
        if (is_dir($dir)) {
            continue;
        }
        mkdir($dir, $mode);
    }
}
/**
 * Tworzy plik oraz katalogi, jeżeli ich brakuje
 *
 * @param $filepath string Ścieżka absolutna do pliku, który chcesz stworzyć
 * @param int $mode Prawa dostępu do pliku
 * @return void
 */
function makeFile($filepath, $mode = 0775)
{
    makeDirsRecursive(dirname($filepath), $mode);
    if (!file_exists($filepath)) {
        touch($filepath);
    }
    chmod($filepath, $mode);
}

function redirect(string $url, int $code): void
{
    http_response_code($code);
    header ("Location: {$url}");

    exit;
}