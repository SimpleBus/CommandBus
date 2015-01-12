<?php

namespace SimpleBus\Command;

use SimpleBus\Message\Message;

/**
 * A particular type of message: it is imperative by nature.
 */
interface Command extends Message
{
}
