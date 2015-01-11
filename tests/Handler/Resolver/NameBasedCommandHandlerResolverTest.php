<?php

namespace SimpleBus\Command\Tests\Handler\Resolver;

use PHPUnit_Framework_TestCase;
use SimpleBus\Command\Handler\Resolver\NameBasedCommandHandlerResolver;

class NameBasedCommandHandlerResolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_returns_a_command_handler_from_the_handler_collection_by_its_name()
    {
        $command = $this->dummyCommand();
        $commandName = 'command_name';
        $commandHandler = $this->dummyCommandHandler();

        $commandNameResolver = $this->stubCommandNameResolver($command, $commandName);
        $commandHandlerCollection = $this->stubCommandHandlerCollection([$commandName => $commandHandler]);

        $nameBasedHandlerResolver = new NameBasedCommandHandlerResolver(
            $commandNameResolver,
            $commandHandlerCollection
        );

        $this->assertSame($commandHandler, $nameBasedHandlerResolver->resolve($command));
    }

    private function dummyCommandHandler()
    {
        return $this->getMock('SimpleBus\Command\Handler\CommandHandler');
    }

    private function dummyCommand()
    {
        return $this->getMock('SimpleBus\Command\Command');
    }

    private function stubCommandNameResolver($command, $commandName)
    {
        $commandNameResolver = $this->getMock('SimpleBus\Command\Handler\Resolver\Name\CommandNameResolver');

        $commandNameResolver
            ->expects($this->any())
            ->method('resolve')
            ->with($this->identicalTo($command))
            ->will($this->returnValue($commandName));

        return $commandNameResolver;
    }

    private function stubCommandHandlerCollection(array $commandHandlersByCommandName)
    {
        $commandHandlerCollection = $this->getMock('SimpleBus\Command\Handler\Collection\CommandHandlerCollection');
        $commandHandlerCollection
            ->expects($this->any())
            ->method('getByCommandName')
            ->will(
                $this->returnCallback(
                    function ($commandName) use ($commandHandlersByCommandName) {
                        return $commandHandlersByCommandName[$commandName];
                    }
                )
            );

        return $commandHandlerCollection;
    }
}
