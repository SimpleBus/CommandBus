<?php

namespace SimpleBus\Command\Handler\Resolver;

use SimpleBus\Command\Command;
use SimpleBus\Command\Handler\Collection\CommandHandlerCollection;
use SimpleBus\Command\Handler\Resolver\Name\CommandNameResolver;

class NameBasedCommandHandlerResolver implements CommandHandlerResolver
{
    /**
     * @var CommandNameResolver
     */
    private $commandNameResolver;
    /**
     * @var \SimpleBus\Command\Handler\Collection\CommandHandlerCollection
     */
    private $commandHandlers;

    public function __construct(CommandNameResolver $commandNameResolver, CommandHandlerCollection $commandHandlers)
    {
        $this->commandNameResolver = $commandNameResolver;
        $this->commandHandlers = $commandHandlers;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Command $command)
    {
        $name = $this->commandNameResolver->resolve($command);

        return $this->commandHandlers->getByCommandName($name);
    }
}
