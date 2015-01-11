<?php

namespace SimpleBus\Command\Bus;

use SimpleBus\Command\Bus;
use SimpleBus\Command\Command;
use SimpleBus\Command\Handler\Resolver\CommandHandlerResolver;

class DelegatesToCommandHandlers implements CommandBus
{
    use RemembersNext;

    private $commandHandlerResolver;

    public function __construct(CommandHandlerResolver $commandHandlerResolver)
    {
        $this->commandHandlerResolver = $commandHandlerResolver;
    }

    public function handle(Command $command)
    {
        $this->commandHandlerResolver->resolve($command)->handle($command);

        $this->next($command);
    }
}
