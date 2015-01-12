<?php

namespace SimpleBus\Command\Bus\Middleware;

use SimpleBus\Command\Bus;
use SimpleBus\Command\Command;
use SimpleBus\Command\Handler\Resolver\CommandHandlerResolver;

class DelegatesToCommandHandlers implements CommandBusMiddleware
{
    private $commandHandlerResolver;

    public function __construct(CommandHandlerResolver $commandHandlerResolver)
    {
        $this->commandHandlerResolver = $commandHandlerResolver;
    }

    public function handle(Command $command, callable $next)
    {
        $this->commandHandlerResolver->resolve($command)->handle($command);

        $next($command);
    }
}
