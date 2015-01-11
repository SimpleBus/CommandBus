<?php

namespace SimpleBus\Command\Handler\Collection;

use SimpleBus\Command\Handler\Collection\Exception\NoHandlerForCommandName;
use SimpleBus\Command\Handler\CommandHandler;

interface CommandHandlerCollection
{
    /**
     * @param string $commandName
     * @throws NoHandlerForCommandName
     * @return CommandHandler
     */
    public function getByCommandName($commandName);
}
