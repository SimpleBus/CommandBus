<?php

namespace SimpleBus\Command\Handler\Collection\Exception;

use LogicException;

class CouldNotLoadHandlerService extends LogicException
{
    public function __construct($handlerServiceId, \Exception $previous = null)
    {
        parent::__construct(
            sprintf(
                'Could not load a command handler with service id "%s"',
                $handlerServiceId
            ),
            null,
            $previous
        );
    }
}
