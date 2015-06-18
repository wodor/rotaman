<?php

namespace spec\Lunchbot\Infrastructure;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Lunchbot\Infrastructure\Command;
use Lunchbot\Infrastructure\CommandHandler;

class CommandBusSpec extends ObjectBehavior
{
//    function it_throws_exception_with_bad_class_type(Command $command)
//    {
//        $this->shouldThrow();
//        $badClass = new \stdClass;
//        $this->beConstructedWith([$badClass]);
//        $this->dispatch($command);
//    }

    function it_puts_a_command_on_the_bus(CommandHandler $handler, Command $command)
    {
        $handler->handle($command)->willReturn(true);

        $this->beConstructedWith([$handler]);

        $this->dispatch($command)->shouldReturn(true);
    }
}
