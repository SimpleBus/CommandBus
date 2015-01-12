<?php

namespace SimpleBus\Command\Bus;

use SimpleBus\Command\Command;

class CommandBusStack implements CommandBus
{
    /**
     * @var StackedCommandBus[]
     */
    private $stackedCommandBuses = [];

    public function __construct(array $commandBuses = [])
    {
        foreach ($commandBuses as $commandBus) {
            $this->addCommandBus($commandBus);
        }
    }

    public function addCommandBus(StackedCommandBus $commandBus)
    {
        $this->stackedCommandBuses[] = $commandBus;
    }

    public function handle(Command $command)
    {
        call_user_func($this->callableForNextCommandBus(0), $command);
    }

    private function callableForNextCommandBus($index)
    {
        if (!isset($this->stackedCommandBuses[$index])) {
            return function() {};
        }

        $stackedCommandBus = $this->stackedCommandBuses[$index];

        return function(Command $command) use ($stackedCommandBus, $index) {
            $stackedCommandBus->handle($command, $this->callableForNextCommandBus($index + 1));
        };
    }
}
