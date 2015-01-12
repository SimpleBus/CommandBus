<?php

namespace SimpleBus\Command\Bus;

use SimpleBus\Command\Command;

interface StackedCommandBus
{
    public function handle(Command $command, callable $next);
}
