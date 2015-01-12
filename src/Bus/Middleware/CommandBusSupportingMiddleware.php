<?php

namespace SimpleBus\Command\Bus\Middleware;

use SimpleBus\Command\Bus\CommandBus;
use SimpleBus\Command\Command;

class CommandBusSupportingMiddleware implements CommandBus
{
    /**
     * @var CommandBusMiddleware[]
     */
    private $middlewares = [];

    public function __construct(array $commandBuses = [])
    {
        foreach ($commandBuses as $commandBus) {
            $this->addMiddleware($commandBus);
        }
    }

    /**
     * Provide new middleware for this command bus. Should only be used at configuration time.
     *
     * @private
     * @param CommandBusMiddleware $middleware
     * @return void
     */
    public function addMiddleware(CommandBusMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function handle(Command $command)
    {
        call_user_func($this->callableForNextMiddleware(0), $command);
    }

    private function callableForNextMiddleware($index)
    {
        if (!isset($this->middlewares[$index])) {
            return function() {};
        }

        $stackedCommandBus = $this->middlewares[$index];

        return function(Command $command) use ($stackedCommandBus, $index) {
            $stackedCommandBus->handle($command, $this->callableForNextMiddleware($index + 1));
        };
    }
}
