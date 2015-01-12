<?php

namespace SimpleBus\Command\Bus\Middleware;

use SimpleBus\Command\Command;

interface CommandBusMiddleware
{
    /**
     * The provided $next callable should be called whenever the next middleware should start handling the command.
     * Its only argument should be a Command object (usually the same as the originally provided command).
     *
     * @param Command $command
     * @param callable $next
     * @return void
     */
    public function handle(Command $command, callable $next);
}
