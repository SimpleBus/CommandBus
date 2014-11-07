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
        const NAME = 'register_user';

        public $email;

        public function name()
        {
            return self::NAME;
        }
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

3. Set up the command bus and the command handler resolver:

    ```php
    use SimpleBus\Command\Bus\DelegatesToCommandHandlers;
    use SimpleBus\Command\Handler\LazyLoadingCommandHandlerResolver;

    $commandHandlerResolver = new LazyLoadingCommandHandlerResolver(
        function ($serviceId) {
            // lazily load/create an instance of the command handler, e.g. using a service locator
             $handler = ...;

             return $handler;
        },
        array(
            RegisterUserCommand::NAME => 'register_user_command_handler_service_id'
        )
    );

    $commandBus = new DelegatesToCommandHandlers($commandHandlerResolver);

    $registerUserCommand = new RegisterUserCommand();
    $registerUserCommand->email = 'matthiasnoback@gmail.com';

    $commandBus->handle($registerUserCommand);
    ```

Because a command handler might call the command bus to handle new commands, it's better to wrap the command bus to make
sure that the first command is fully handled first:

```php
use SimpleBus\Command\Bus\FinishesCommandBeforeHandlingNext;

$commandBusWrapper = new FinishesCommandBeforeHandlingNext();
$commandBusWrapper->setNext($commandBus);

$commandBusWrapper->handle($registerUserCommand);
```

## Extension points

### Specialized command buses

You can add your own specialized command bus implementations. You can chain them using `CommandBus::setNext()`.

If your command bus needs to call the next command bus in the chain, use the `RemembersNext` trait to prevent some code
duplication:

```php
use SimpleBus\Command\Bus\RemembersNext;

class SpecializedCommandBus implements CommandBus
{
    use RemembersNext;

    public function handle(Command $command)
    {
        ...

        // call the next command bus in the chain
        $this->next($command);
    }
}
```

### Load command handlers in a different way

The `DelegatesToCommandHandlers` command bus uses a `CommandHandlerResolver` to find the right handler for a given
command object. You can implement your own strategy for that of course, just make sure your class implements the
`CommandHandlerResolver` interface.
