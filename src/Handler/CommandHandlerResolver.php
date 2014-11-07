<?php

namespace SimpleBus\Command\Handler;

use SimpleBus\Command\Command;

interface CommandHandlerResolver
{
    /**
     * @param Command $command
     * @return CommandHandler
     * @throws \InvalidArgumentException
     */
    public function resolve(Command $command);
}
