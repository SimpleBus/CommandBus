<?php

namespace SimpleBus\Command\Handler\Collection;

use Exception;
use SimpleBus\Command\Handler\Collection\Exception\CouldNotLoadHandlerService;
use SimpleBus\Command\Handler\Collection\Exception\InvalidCommandHandler;
use SimpleBus\Command\Handler\Collection\Exception\NoHandlerForCommandName;
use SimpleBus\Command\Handler\CommandHandler;

class LazyLoadingCommandHandlerCollection implements CommandHandlerCollection
{
    /**
     * @var array
     */
    private $commandHandlerServiceIds;

    /**
     * @var callable
     */
    private $serviceLocator;

    public function __construct(array $commandHandlerServiceIds, callable $serviceLocator)
    {
        $this->commandHandlerServiceIds = $commandHandlerServiceIds;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * {@inheritdoc}
     * @throws CouldNotLoadHandlerService
     * @throws InvalidCommandHandler
     */
    public function getByCommandName($commandName)
    {
        if (!isset($this->commandHandlerServiceIds[$commandName])) {
            throw new NoHandlerForCommandName($commandName);
        }

        return $this->loadHandlerService($this->commandHandlerServiceIds[$commandName]);
    }

    private function loadHandlerService($handlerServiceId)
    {
        try {
            $commandHandler = call_user_func($this->serviceLocator, $handlerServiceId);
        } catch (Exception $previous) {
            throw new CouldNotLoadHandlerService($handlerServiceId, $previous);
        }

        if (!($commandHandler instanceof CommandHandler)) {
            throw new InvalidCommandHandler($commandHandler);
        }

        return $commandHandler;
    }
}
