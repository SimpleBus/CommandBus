<?php

namespace SimpleBus\Command\Handler\Resolver\Name;

use SimpleBus\Command\Command;

interface CommandNameResolver
{
    /**
     * Resolve the unique name of a command, e.g. the full class name
     *
     * @param Command $command
     * @return string
     */
    public function resolve(Command $command);
}
