# SimpleBus

[![Build Status](https://travis-ci.org/SimleBus/CommandBus.svg?branch=master)](https://travis-ci.org/SimpleBus/CommandBus)

By [Matthias Noback](http://php-and-symfony.matthiasnoback.nl/)

## Installation

Using Composer:

    composer require simple-bus/command-bus

## Usage

1. Create a command

    ```php
    use SimpleBus\Command\Command;

    class RegisterUserCommand implements Command
    {
        public $email;
    }
    ```

2. Create a command handler

    ```php
    use SimpleBus\Command\Command;
    use SimpleBus\Command\Handler\CommandHandler;

    class RegisterUserCommandHandler implements CommandHandler
    {
        public function handle(Command $command)
        {
            // do anything to handle the command
        }
    }
    ```

3. Set up the command bus and the command handlers:

    ```php

    use SimpleBus\Command\Handler\Collection\LazyLoadingCommandHandlerCollection;
    use SimpleBus\Command\Handler\Resolver\Name\ClassBased;
    use SimpleBus\Command\Handler\LazyLoadingCommandHandlerResolver;
    use SimpleBus\Command\Bus\Middleware\CommandBusSupportingMiddleware;
    use SimpleBus\Command\Bus\Middleware\DelegatesToCommandHandlers;
    use SimpleBus\Command\Handler\Resolver\NameBasedCommandHandlerResolver;

    $commandHandlerCollection = new LazyLoadingCommandHandlerCollection(
        array(
            RegisterUserCommand::CLASS => 'register_user_command_handler_service_id'
        ),
        function ($serviceId) {
            // lazily load/create an instance of the command handler, e.g. using a service locator
            $handler = ...;

            return $handler;
        }
    );
    $commandHandlerResolver = new NameBasedCommandHandlerResolver(
        // we use the class-based command name resolver
        new ClassBased(),
        $commandHandlerCollection
    );

    $commandBus = CommandBusSupportingMiddleware();
    $commandBus->addMiddleware(new DelegatesToCommandHandlers($commandHandlerResolver));

    $registerUserCommand = new RegisterUserCommand();
    $registerUserCommand->email = 'matthiasnoback@gmail.com';

    $commandBus->handle($registerUserCommand);
    ```

Because a command handler might call the command bus to handle new commands, it's better to use the
`FinishesCommandBeforeHandlingNext` middleware to make sure that the first command is fully handled first:

```php
use SimpleBus\Command\Bus\Middleware\FinishesCommandBeforeHandlingNext;

$commandBus->addMiddleware(new FinishesCommandBeforeHandlingNext());
```

## Extension points

### Specialized command bus middleware

You can add your own specialized command bus middleware.

When your command bus middleware needs to call the next middleware in the chain, call the provided `$next` callable with
the `$command` object as the only argument:

```php
use SimpleBus\Command\Bus\Middleware\CommandBusMiddleware;

class SpecializedCommandBusMiddleware implements CommandBusMiddleware
{
    public function handle(Command $command, callable $next)
    {
        ...

        // call the next middleware in the chain
        $next($command);
    }
}
```

### Resolvers

- The command name is resolved to its fully qualified class name. You can override this behavior by supplying a
different implementation for `SimpleBus\Command\Handler\Resolver\Name\CommandNameResolver`.
- The right command handler is determined based on the command name. You can override this behavior by suppling a
different implementation for `SimpleBus\Command\Handler\Resolver\CommandHandlerResolver`.
