<?php

namespace SimpleBus\Command\Tests\Command;

use SimpleBus\Command\Bus\Middleware\CommandBusSupportingMiddleware;
use SimpleBus\Command\Command;
use SimpleBus\Command\Bus\Middleware\FinishesCommandBeforeHandlingNext;
use SimpleBus\Command\Tests\Bus\Fixtures\StubCommandBus;

class FinishesCommandBeforeHandlingNextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_finishes_handling_a_command_before_handling_the_next()
    {
        $originalCommand = $this->dummyCommand();
        $newCommand = $this->dummyCommand();
        $whatHappened = [];

        $commandBus = new CommandBusSupportingMiddleware();
        $commandBus->addMiddleware(new FinishesCommandBeforeHandlingNext());
        $commandBus->addMiddleware(
            // the next command bus that will be called
            new StubCommandBus(
                function (Command $actualCommand) use ($originalCommand, $newCommand, $commandBus, &$whatHappened) {
                    $handledCommands[] = $actualCommand;

                    if ($actualCommand === $originalCommand) {
                        $whatHappened[] = 'start handling original command';
                        // while handling the original we trigger a new command
                        $commandBus->handle($newCommand);
                        $whatHappened[] = 'finished handling original command';
                    } elseif ($actualCommand === $newCommand) {
                        $whatHappened[] = 'start handling new command';
                        $whatHappened[] = 'finished handling new command';
                    }
                }
            )
        );

        $commandBus->handle($originalCommand);

        $this->assertSame(
            [
                'start handling original command',
                'finished handling original command',
                'start handling new command',
                'finished handling new command'
            ],
            $whatHappened
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Command
     */
    private function dummyCommand()
    {
        return $this->getMock('SimpleBus\Command\Command');
    }
}
