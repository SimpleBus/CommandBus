<?php

namespace SimpleBus\Command\Tests\Bus;

use SimpleBus\Command\Bus\Middleware\DelegatesToCommandHandlers;
use SimpleBus\Command\Command;

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

        $commandBus = new \SimpleBus\Command\Bus\Middleware\DelegatesToCommandHandlers($commandHandlerResolver);

        $nextIsCalled = false;
        $next = function(Command $actualCommand) use (&$nextIsCalled, $command) {
            $this->assertSame($command, $actualCommand);
            $nextIsCalled = true;
        };

        $commandBus->handle($command, $next);

        $this->assertTrue($nextIsCalled);
    }

    private function dummyCommand()
    {
        return $this->getMock('SimpleBus\Command\Command');
    }

    private function mockCommandHandlerShouldHandle(Command $command)
    {
        $commandHandler = $this->getMock('SimpleBus\Command\Handler\CommandHandler');

        $commandHandler
            ->expects($this->once())
            ->method('handle')
            ->with($this->identicalTo($command));

        return $commandHandler;
    }

    private function mockCommandHandlerResolverShouldResolve($command, $resolvedCommandHandler)
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
