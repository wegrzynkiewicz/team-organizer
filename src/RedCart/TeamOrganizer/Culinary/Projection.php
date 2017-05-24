<?php

declare(strict_types=1);

namespace RedCart\TeamOrganizer\Culinary;

class Projection
{
    /** @var string */
    private $restaurant;

    /** @var Declaration[] */
    private $declarations;

    public function __construct(string $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    public function addDeclaration(Declaration $declaration)
    {
        $this->declarations[] = $declaration;
    }

    /**
     * @param Declaration[] $declarations
     */
    public function addDeclarations(array $declarations)
    {
        foreach ($declarations as $declaration) {
            $this->declarations[] = $declaration;
        }
    }

    public function totalPersons(): int
    {
        $sum = 0;
        foreach ($this->declarations as $declaration) {
            $sum += $declaration->countPersons();
        }

        return $sum;
    }

    public function totalPrice(): float
    {
        $sum = 0.0;
        foreach ($this->declarations as $declaration) {
            $sum += $declaration->getPrice();
        }

        return $sum;
    }

    /**
     * @return string
     */
    public function getRestaurant(): string
    {
        return $this->restaurant;
    }

    /**
     * @return Declaration[]
     */
    public function getDeclarations(): array
    {
        return $this->declarations;
    }
}