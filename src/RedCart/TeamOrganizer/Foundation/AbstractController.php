<?php

declare(strict_types=1);

namespace RedCart\TeamOrganizer\Foundation;

abstract class AbstractController
{
    /** @var Container */
    private $container;

    final public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    public function get(string $service)
    {
        return $this->container->get($service);
    }
}