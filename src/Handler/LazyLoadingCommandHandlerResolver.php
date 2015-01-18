<?php

namespace SimpleBus\Command\Handler;

use Assert\Assertion;
use SimpleBus\Command\Command;

class LazyLoadingCommandHandlerResolver implements CommandHandlerResolver
{
    private $commandHandlers;
    private $serviceLocator;

    public function __construct(callable $serviceLocator, array $commandHandlers)
    {
        $this->commandHandlers = $commandHandlers;
        $this->serviceLocator = $serviceLocator;
    }

    public function resolve(Command $command)
    {
        Assertion::string(
            $command->__messageName(),
            sprintf(
                '%s::name() should return a string',
                get_class($command)
            )
        );

        if (!isset($this->commandHandlers[$command->__messageName()])) {
            throw new \InvalidArgumentException(
                sprintf(
                    'No valid handler found for command "%s"',
                    $command->__messageName()
                )
            );
        }

        $serviceId = $this->commandHandlers[$command->__messageName()];
        $commandHandler = call_user_func($this->serviceLocator, $serviceId);

        Assertion::isInstanceOf(
            $commandHandler,
            'SimpleBus\Command\Handler\CommandHandler'
        );

        return $commandHandler;
    }
}
