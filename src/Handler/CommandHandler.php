<?php

namespace SimpleBus\Command\Handler;

use SimpleBus\Command\Command;

interface CommandHandler
{
    /**
     * @return void
     */
    public function handle(Command $command);
}
