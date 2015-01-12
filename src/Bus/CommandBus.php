<?php

namespace SimpleBus\Command\Bus;

use SimpleBus\Command\Command;

interface CommandBus
{
    /**
     * @param Command $command
     * @return void
     */
    public function handle(Command $command);
}
