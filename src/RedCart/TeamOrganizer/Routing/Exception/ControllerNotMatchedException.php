<?php

declare(strict_types=1);

namespace RedCart\TeamOrganizer\Routing\Exception;

use RedCart\TeamOrganizer\Exception\AbstractException;

/**
 * Rzucany w przypadku kiedy żaden kontroler nie został dopasowany
 */
class ControllerNotMatchedException extends AbstractException
{

}