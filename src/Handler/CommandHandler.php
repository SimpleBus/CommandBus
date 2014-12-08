<?php

namespace SimpleBus\Command\Handler;

interface CommandHandler
{
    /**
     * @param $command
     * @return void
     */
    public function handle($command);
}
