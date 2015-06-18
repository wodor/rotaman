<?php

namespace spec\Lunchbot\Infrastructure;

use Lunchbot\Infrastructure\Event;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventBusSpec extends ObjectBehavior
{
    function it_puts_an_event_on_the_bus(Event $event)
    {
        $this->trigger($event);
        $this->getEvents()->shouldReturn([$event]);
    }
}
