<?php

namespace SimpleBus\Command\Tests\Handler\Resolver\Name;

use SimpleBus\Command\Handler\Resolver\Name\ClassBased;
use SimpleBus\Command\Tests\Handler\Resolver\Fixtures\DummyCommand;

class ClassBasedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_returns_the_full_class_name_as_the_unique_name_of_a_command()
    {
        $resolver = new ClassBased();
        $command = new DummyCommand();
        $this->assertSame(
            'SimpleBus\Command\Tests\Handler\Resolver\Fixtures\DummyCommand',
            $resolver->resolve($command)
        );
    }
}
