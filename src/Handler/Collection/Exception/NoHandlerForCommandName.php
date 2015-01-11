<?php

namespace SimpleBus\Command\Handler\Collection\Exception;

use LogicException;

class NoHandlerForCommandName extends \LogicException
{
    public function __construct($commandName)
    {
        parent::__construct(
            sprintf(
                'There is no command handler for command "%s"',
                $commandName
            )
        );
    }
}
