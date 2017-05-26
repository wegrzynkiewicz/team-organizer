<?php

declare(strict_types=1);

namespace RedCart\TeamOrganizer\Routing\Exception;

use RedCart\TeamOrganizer\Exception\AbstractException;

/**
 * Rzucany w przypadku kiedy żadanie zostało dopasowane, ale wybrany kontroler nie istnieje
 */
class ControllerNotFoundException extends AbstractException
{

}