<?php

namespace SimpleBus\Command\Tests\Bus;

use SimpleBus\Command\Bus\Middleware\DelegatesToCommandHandlers;
use SimpleBus\Command\Command;
use SimpleBus\Command\Handler\CommandHandler;
use SimpleBus\Command\Handler\Resolver\CommandHandlerResolver;

class DelegatesToCommandHandlersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_resolves_the_command_handler_and_lets_it_handle_the_command()
    {
        $command = $this->dummyCommand();
        $commandHandler = $this->mockCommandHandlerShouldHandle($command);
        $commandHandlerResolver = $this->mockCommandHandlerResolverShouldResolve($command, $commandHandler);

        $commandBus = new DelegatesToCommandHandlers($commandHandlerResolver);

        $nextIsCalled = false;
        $next = function(Command $actualCommand) use (&$nextIsCalled, $command) {
            $this->assertSame($command, $actualCommand);
            $nextIsCalled = true;
        };

        $commandBus->handle($command, $next);

        $this->assertTrue($nextIsCalled);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Command
     */
    private function dummyCommand()
    {
        return $this->getMock('SimpleBus\Command\Command');
    }

    /**
     * @param Command $command
     * @return \PHPUnit_Framework_MockObject_MockObject|CommandHandler
     */
    private function mockCommandHandlerShouldHandle(Command $command)
    {
        $commandHandler = $this->getMock('SimpleBus\Command\Handler\CommandHandler');

        $commandHandler
            ->expects($this->once())
            ->method('handle')
            ->with($this->identicalTo($command));

        return $commandHandler;
    }

    /**
     * @param Command $command
     * @param CommandHandler $resolvedCommandHandler
     * @return \PHPUnit_Framework_MockObject_MockObject|CommandHandlerResolver
     */
    private function mockCommandHandlerResolverShouldResolve(Command $command, CommandHandler $resolvedCommandHandler)
    {
        $commandHandlerResolver = $this->getMock('SimpleBus\Command\Handler\Resolver\CommandHandlerResolver');

        $commandHandlerResolver
            ->expects($this->once())
            ->method('resolve')
            ->with($this->identicalTo($command))
            ->will($this->returnValue($resolvedCommandHandler));

        return $commandHandlerResolver;
    }
}
