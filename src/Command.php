<?php

namespace SimpleBus\Command;

interface Command
{
    /**
     * @return string
     */
    public static function __messageName();
}
