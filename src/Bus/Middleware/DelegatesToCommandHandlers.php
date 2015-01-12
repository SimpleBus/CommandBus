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

    /**
     * Handles the command by resolving the correct CommandHandler and calling it.
     *
     * {@inheritdoc}
     */
    public function handle(Command $command, callable $next)
    {
        $this->commandHandlerResolver->resolve($command)->handle($command);

        $next($command);
    }
}
