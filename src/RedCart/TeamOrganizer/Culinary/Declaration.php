<?php

declare(strict_types=1);

namespace RedCart\TeamOrganizer\Culinary;

use JsonSerializable;
use RedCart\TeamOrganizer\Culinary\Exception\InvalidDeclaration;

class Declaration implements JsonSerializable
{
    /** @var array */
    private $persons = [];

    /** @var string */
    private $restaurant;

    /** @var string */
    private $description;

    /** @var float */
    private $price;

    public function __construct(array $data)
    {
        if (!is_array($data['persons'] ?? null)) {
            throw new InvalidDeclaration();
        }

        $this->persons = $data['persons'];
        $this->restaurant = $data['restaurant'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->price = floatval($data['price']) ?? 0.0;
    }

    function jsonSerialize()
    {
        return [
            'persons' => $this->persons,
            'restaurant' => $this->restaurant,
            'description' => $this->description,
            'price' => $this->price,
        ];
    }

    /**
     * @return array
     */
    public function getPersons(): array
    {
        return $this->persons;
    }

    /**
     * @return int
     */
    public function countPersons(): int
    {
        return count($this->persons);
    }

    /**
     * @return string
     */
    public function getRestaurant(): string
    {
        return $this->restaurant;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }
}