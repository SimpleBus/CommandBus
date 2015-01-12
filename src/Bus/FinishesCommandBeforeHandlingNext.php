<?php

namespace SimpleBus\Command\Bus;

use SimpleBus\Command\Command;

class FinishesCommandBeforeHandlingNext implements StackedCommandBus
{
    /**
     * @var array
     */
    private $queue = [];

    /**
     * @var bool
     */
    private $isHandling = false;

    /**
     * {@inheritdoc}
     */
    public function handle(Command $command, callable $next)
    {
        $this->queue[] = $command;

        if (!$this->isHandling) {
            $this->isHandling = true;

            while ($command = array_shift($this->queue)) {
                $next($command);
            }

            $this->isHandling = false;
        }
    }
}
