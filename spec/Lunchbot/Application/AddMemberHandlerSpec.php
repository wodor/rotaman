<?php

namespace spec\Lunchbot\Application;

use Everzet\PersistedObjects\AccessorObjectIdentifier;
use Everzet\PersistedObjects\InMemoryRepository;
use Lunchbot\Infrastructure\EventBus;
use Lunchbot\Persistent\MembersInMemory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AddMemberHandlerSpec extends ObjectBehavior
{
    function it_handles_adding_a_new_member()
    {
        $this->beConstructedWith(
            new EventBus(),
            new MembersInMemory(
                new InMemoryRepository(
                    new AccessorObjectIdentifier('getId')
                )
            )
        );
    }
}
