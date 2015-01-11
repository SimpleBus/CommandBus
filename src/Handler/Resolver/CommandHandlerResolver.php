<?php

namespace SimpleBus\Command\Handler\Resolver;

use SimpleBus\Command\Command;
use SimpleBus\Command\Handler\CommandHandler;

interface CommandHandlerResolver
{
    /**
     * @param Command $command
     * @return CommandHandler
     * @throws \InvalidArgumentException
     */
    public function resolve(Command $command);
}
