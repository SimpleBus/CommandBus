<?php

namespace SimpleBus\Command\Handler;

use SimpleBus\Command\Command;

interface CommandHandler
{
    /**
     * Handle the given command.
     *
     * @param Command $command
     * @return void
     */
    public function handle(Command $command);
}
