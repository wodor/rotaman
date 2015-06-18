<?php

namespace spec\Lunchbot\Application;

use Everzet\PersistedObjects\AccessorObjectIdentifier;
use Everzet\PersistedObjects\InMemoryRepository;
use Lunchbot\Infrastructure\EventBus;
use Lunchbot\Model\Member\AddMember;
use Lunchbot\Persistent\MembersInMemory;
use Lunchbot\ValueObject\Member\MemberId;
use Lunchbot\ValueObject\Member\Name;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AddMemberHandlerSpec extends ObjectBehavior
{
    function it_handles_adding_a_new_member()
    {
        $members = new MembersInMemory(
            new InMemoryRepository(
                new AccessorObjectIdentifier('getId')
            )
        );
        $this->beConstructedWith(new EventBus(), $members);

        $memberId = new MemberId('aria');
        $name = new Name('Aria');

        $this->handle(
            new AddMember($memberId, $name)
        );

        $member = $members->all()[0];

        $member->getId();
        $member->getName();
    }
}
