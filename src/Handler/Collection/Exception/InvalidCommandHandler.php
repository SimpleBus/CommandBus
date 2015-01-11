<?php

namespace SimpleBus\Command\Handler\Collection\Exception;

use LogicException;

class InvalidCommandHandler extends \LogicException
{
    public function __construct($commandHandler)
    {
        parent::__construct(
            sprintf(
                'Expected an object of type "SimpleBus\Command\Handler\CommandHandler", got "%s"',
                $this->typeOf($commandHandler)
            )
        );
    }

    private function typeOf($commandHandler)
    {
        if (is_object($commandHandler)) {
            return get_class($commandHandler);
        }

        return gettype($commandHandler);
    }
}
