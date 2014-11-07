<?php

namespace SimpleBus\Command\Bus;

use SimpleBus\Command\Command;

interface CommandBus
{
    /**
     * @return void
     */
    public function handle(Command $command);

    /**
     * @return void
     */
    public function setNext(CommandBus $commandBus);
}
