<?php

declare(strict_types=1);

namespace Redcart\TeamOrganizer\Foundation;

use Redcart\TeamOrganizer\Foundation\Exception\DependencyException;

class Container
{
    private $services = [];

    /** @var ServiceProviderInterface[] */
    private $providers = [];

    public function __get(string $name)
    {
        return $this->get($name);
    }

    public function __isset(string $name)
    {
        return $this->has($name);
    }

    public function get(string $name)
    {
        if (!isset($this->services[$name])) {
            if (isset($this->providers[$name])) {
                $this->provideService($name);
            } else {
                throw new DependencyException(sprintf(
                    "Service named (%s) does not exists", $name
                ));
            }
        }

        return $this->services[$name];
    }

    public function has(string $name): bool
    {
        if (isset($this->services[$name])) {
            return true;
        }

        if (isset($this->providers[$name])) {
            return true;
        }

        return false;
    }

    public function registerProvider(ServiceProviderInterface $provider): void
    {
        $name = $provider->getServiceName();
        if (isset($this->providers[$name])) {
            throw new DependencyException(sprintf(
                "Service provider named (%s) already registered", $name
            ));
        }

        $this->providers[$name] = $provider;
    }

    public function set(string $name, $service)
    {
        if (isset($this->services[$name])) {
            throw new DependencyException(sprintf(
                "Service named (%s) already exists", $name
            ));
        }

        if (isset($this->providers[$name])) {
            throw new DependencyException(sprintf(
                "Service provider named (%s) already exists", $name
            ));
        }

        $this->services[$name] = $service;
    }

    private function provideService(string $name): void
    {
        $this->services[$name] = $this->providers[$name]->provide();
    }
}