<?php

namespace SimpleBus\Tests\Command;

use SimpleBus\Command\Command;
use SimpleBus\Command\Bus\CommandBus;
use SimpleBus\Command\Bus\FinishesCommandBeforeHandlingNext;

class FinishesCommandBeforeHandlingNextTest extends \PHPUnit_Framework_TestCase
{
    /** @var \SimpleBus\Command\Bus\FinishesCommandBeforeHandlingNext */
    private $commandBus;

    /** @var \PHPUnit_Framework_MockObject_MockObject|CommandBus */
    private $next;

    protected function setUp()
    {
        $this->commandBus = new FinishesCommandBeforeHandlingNext();
        $this->next = $this->mockCommandBus();
        $this->commandBus->setNext($this->next);
    }

    /**
     * @test
     */
    public function it_forwards_the_handle_call_to_the_next_command_bus()
    {
        $command = $this->dummyCommand();

        $this->next
            ->expects($this->once())
            ->method('handle')
            ->with($this->identicalTo($command));

        $this->commandBus->handle($command);
    }

    /**
     * @test
     */
    public function it_handles_commands_in_the_original_order_if_extra_commands_are_added_while_handling_the_first()
    {
        $originalCommand = $this->dummyCommand();
        $commandTriggeredByOriginalCommand = $this->dummyCommand();

        $orderOfEvents = array();

        $commandBus = $this->commandBus;

        $this->next
            ->expects($this->any())
            ->method('handle')
            ->will(
                $this->returnCallback(
                    function ($command) use ($commandBus, $commandTriggeredByOriginalCommand, &$orderOfEvents) {
                        $orderOfEvents[] = $command;
                        if ($command !== $commandTriggeredByOriginalCommand) {
                            $commandBus->handle($commandTriggeredByOriginalCommand);
                            $orderOfEvents[] = 'finished handling original command';
                        }
                    }
                )
            );

        $this->commandBus->handle($originalCommand);

        $this->assertSame(
            array(
                $originalCommand,
                'finished handling original command',
                $commandTriggeredByOriginalCommand
            ),
            $orderOfEvents
        );
    }

    private function mockCommandBus()
    {
        return $this->getMock('SimpleBus\Command\Bus\CommandBus');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Command
     */
    private function dummyCommand()
    {
        return $this->getMock('SimpleBus\Command\Command');
    }
}
