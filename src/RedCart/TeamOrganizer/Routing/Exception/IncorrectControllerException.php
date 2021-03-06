<?php

declare(strict_types=1);

namespace RedCart\TeamOrganizer\Routing\Exception;

use RedCart\TeamOrganizer\Exception\AbstractException;

/**
 * Rzucany w przypadku kiedy kontroler jest uszkodzony chciaż został dopasowany
 */
class IncorrectControllerException extends AbstractException
{

}