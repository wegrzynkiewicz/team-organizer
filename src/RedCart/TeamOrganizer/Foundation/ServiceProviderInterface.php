<?php

declare(strict_types=1);

namespace RedCart\TeamOrganizer\Foundation;

interface ServiceProviderInterface
{
    public function getServiceName();

    public function provide();
}