<?php

namespace SimpleBus\Command\Bus;

use SimpleBus\Command\Command;

trait RemembersNext
{
    private $next;

    public function setNext(CommandBus $commandBus)
    {
        $this->next = $commandBus;
    }

    protected function next(Command $command)
    {
        if ($this->next instanceof CommandBus) {
            $this->next->handle($command);
        }
    }
}
