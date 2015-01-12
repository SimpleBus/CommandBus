<?php

namespace SimpleBus\Command\Bus\Middleware;

use SimpleBus\Command\Command;

interface CommandBusMiddleware
{
    public function handle(Command $command, callable $next);
}
