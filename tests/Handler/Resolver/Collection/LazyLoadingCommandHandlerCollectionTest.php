<?php

namespace SimpleBus\Command\Tests\Handler\Collection;

use SimpleBus\Command\Handler\Collection\LazyLoadingCommandHandlerCollection;
use stdClass;

class LazyLoadingCommandHandlerCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_loads_a_known_command_handler()
    {
        $commandHandlerService = $this->dummyCommandHandler();

        $collection = new LazyLoadingCommandHandlerCollection(
            [
                'Known\Command' => 'known_command_handler_service_id'
            ],
            $this->stubServiceLocator(
                [
                    'known_command_handler_service_id' => $commandHandlerService
                ]
            )
        );

        $this->assertSame($commandHandlerService, $collection->getByCommandName('Known\Command'));
    }

    /**
     * @test
     */
    public function it_fails_when_there_is_no_command_handler_for_the_given_name()
    {
        $collection = new LazyLoadingCommandHandlerCollection(
            [],
            $this->stubServiceLocator([])
        );

        $this->setExpectedException(
            'SimpleBus\Command\Handler\Collection\Exception\NoHandlerForCommandName'
        );
        $collection->getByCommandName('Unknown\Command');
    }

    /**
     * @test
     */
    public function it_fails_when_the_handler_returned_by_the_service_locator_is_not_an_object_of_the_right_class()
    {
        $collection = new LazyLoadingCommandHandlerCollection(
            [
                'Command\Name' => 'not_a_command_handler_service_of_the_right_class_id'
            ],
            $this->stubServiceLocator(
                [
                    'not_a_command_handler_service_of_the_right_class_id' => new stdClass()
                ]
            )
        );

        $this->setExpectedException('SimpleBus\Command\Handler\Collection\Exception\InvalidCommandHandler');
        $collection->getByCommandName('Command\Name');
    }

    /**
     * @test
     */
    public function it_fails_when_the_handler_returned_by_the_service_locator_is_not_an_object()
    {
        $collection = new LazyLoadingCommandHandlerCollection(
            [
                'Command\Name' => 'not_an_object_service_id'
            ],
            $this->stubServiceLocator(
                [
                    'not_an_object_service_id' => 'not an object'
                ]
            )
        );

        $this->setExpectedException('SimpleBus\Command\Handler\Collection\Exception\InvalidCommandHandler');
        $collection->getByCommandName('Command\Name');
    }

    /**
     * @test
     */
    public function it_fails_when_the_service_locator_fails_to_load_the_command_handler_service()
    {
        $collection = new LazyLoadingCommandHandlerCollection(
            [
                'Command\Name' => 'invalid_command_handler_service_id'
            ],
            function () {
                throw new \Exception('Always failing service locator');
            }
        );

        $this->setExpectedException('SimpleBus\Command\Handler\Collection\Exception\CouldNotLoadHandlerService');
        $collection->getByCommandName('Command\Name');
    }

    private function dummyCommandHandler()
    {
        return $this->getMock('SimpleBus\Command\Handler\CommandHandler');
    }

    private function stubServiceLocator(array $knownServices)
    {
        return function ($id) use ($knownServices) {
            if (!isset($knownServices[$id])) {
                $this->fail('Unknown service requested');
            }

            return $knownServices[$id];
        };
    }
}
