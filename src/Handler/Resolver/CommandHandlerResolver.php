<?php

namespace SimpleBus\Command\Handler\Resolver;

use SimpleBus\Command\Command;
use SimpleBus\Command\Handler\CommandHandler;

interface CommandHandlerResolver
{
    /**
     * Resolve the CommandHandler for the given Command.
     *
     * @param Command $command
     * @return CommandHandler
     */
    public function resolve(Command $command);
}
