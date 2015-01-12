<?php

namespace SimpleBus\Command\Handler\Resolver\Name;

use SimpleBus\Command\Command;

class ClassBased implements CommandNameResolver
{
    /**
     * The unique name of a command is assumed to be its fully qualified class name
     *
     * {@inheritdoc}
     */
    public function resolve(Command $command)
    {
        return get_class($command);
    }
}
