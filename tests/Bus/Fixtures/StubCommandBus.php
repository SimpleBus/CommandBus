<?php

namespace SimpleBus\Command\Tests\Bus\Fixtures;

use SimpleBus\Command\Bus\Middleware\CommandBusMiddleware;
use SimpleBus\Command\Command;

class StubCommandBus implements CommandBusMiddleware
{
    /**
     * @var callable
     */
    private $handler;

    public function __construct(callable $handler)
    {
        $this->handler = $handler;
    }

    public function handle(Command $command, callable $next)
    {
        call_user_func($this->handler, $command);

        $next();
    }
}
