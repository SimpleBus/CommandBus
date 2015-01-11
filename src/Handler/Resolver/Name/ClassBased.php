<?php

namespace SimpleBus\Command\Handler\Resolver\Name;

use SimpleBus\Command\Command;
use SimpleBus\Command\Handler\Resolver\Name\CommandNameResolver;

class ClassBased implements CommandNameResolver
{
    /**
     * {@inheritdoc}
     */
    public function resolve(Command $command)
    {
        return get_class($command);
    }
}
