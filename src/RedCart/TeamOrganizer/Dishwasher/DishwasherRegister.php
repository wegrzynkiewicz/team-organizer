<?php

declare(strict_types=1);

namespace RedCart\TeamOrganizer\Dishwasher;

use DateTime;
use Exception;

class DishwasherRegister
{
    private $now;
    private $register = [];

    public function __construct()
    {
        $this->now = date('Y-m-d');
        $this->filepath = VAR_PATH . "/dishwasher.json";

        if (!is_readable($this->filepath)) {
            $this->saveEntitiesToFile();
        }

        $this->loadEntitiesFromFile();
    }

    public function mark($employee)
    {
        if (!$this->canMarkToday()) {
            throw new Exception();
        }

        array_unshift($this->register[$employee], $this->now);
        $this->saveEntitiesToFile();
    }

    public function canMarkToday()
    {
        if ($this->getLatestDate() >= $this->now) {
            return false;
        }

        return true;
    }

    public function getLatestDate()
    {
        $dates = array_unchunk($this->register);
        if (count($dates) === 0) {
            throw new Exception();
        }
        $newestDate = max($dates);

        return $newestDate;
    }

    public function getProjection()
    {
        $projections = [];
        foreach ($this->register as $employee => $dates) {
            $newestDate = count($dates) ? max($dates) : '1970-01-01';
            $projections[$employee] = [
                'countDates' => count($dates),
                'newestDate' => DateTime::createFromFormat('Y-m-d', $newestDate),
            ];
        }

        uasort($projections, function ($a, $b) {
            $compare = $a['countDates'] <=> $a['countDates'];
            if ($compare === 0) {
                return $a['newestDate'] > $b['newestDate'];
            }
            return $compare;
        });

        array_multisort(
            array_column($projections, 'countDates'), SORT_ASC,
            array_column($projections, 'newestDate'), SORT_ASC,
            $projections
        );

        return $projections;
    }

    private function saveEntitiesToFile(): void
    {
        $json = json_encode($this->register, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->filepath, $json);
    }

    private function loadEntitiesFromFile(): void
    {
        $json = file_get_contents($this->filepath);
        $this->register = json_decode($json, true);
    }
}