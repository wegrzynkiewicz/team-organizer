<?php

declare(strict_types=1);

namespace RedCart\TeamOrganizer\Culinary;

use RedCart\TeamOrganizer\Culinary\Exception\InvalidDeclaration;

class CulinaryDaysRepository
{
    /** @var string Teraźniejsza data */
    private $now;

    /** @var string Ścieżka do pliku z zapisanymi osobami */
    private $filepath;

    /** @var Declaration[] Tablica zawierająca zapisane deklaracje */
    private $declarations = [];

    public function __construct()
    {
        $this->now = date('Y-m-d');
        $this->filepath = VAR_PATH."/culinary/{$this->now}.json";

        if (!is_readable($this->filepath)) {
            $this->saveDeclarationsToFile();
        }

        $this->loadPersonsFromFile();
    }

    public function getDeclarationByUUID(string $declarationUUID)
    {
        if (!$this->hasDeclarationByUUID($declarationUUID)) {
            throw new InvalidDeclaration(sprintf(
                'Declaration with uuid (%s) does not exits',
                $declarationUUID
            ));
        }

        return $this->declarations[$declarationUUID] ?? [];
    }

    /**
     * @return Declaration[]
     */
    public function getDeclarations(): array
    {
        return $this->declarations;
    }

    public function insertDeclaration(Declaration $declaration): string
    {
        $uuid = uniqid();
        $this->declarations[$uuid] = $declaration;
        $this->saveDeclarationsToFile();

        return $uuid;
    }

    public function removeDeclarationByUUID(string $declarationUUID): void
    {
        unset($this->declarations[$declarationUUID]);
        $this->saveDeclarationsToFile();
    }

    public function hasDeclarationByUUID(string $declarationUUID): bool
    {
        return isset($this->declarations[$declarationUUID]);
    }

    public function updateDeclarationByUUID(Declaration $declaration, string $declarationUUID): void
    {
        $this->declarations[$declarationUUID] = $declaration;
        $this->saveDeclarationsToFile();
    }

    private function saveDeclarationsToFile(): void
    {
        ksort($this->declarations);
        $json = json_encode($this->declarations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->filepath, $json);
        chmod($this->filepath, 0777);
    }

    private function loadPersonsFromFile(): void
    {
        $json = file_get_contents($this->filepath);
        $declarations = json_decode($json, true);
        foreach ($declarations as $uuid => $data) {
            $this->declarations[$uuid] = new Declaration($data);
        }
    }
}