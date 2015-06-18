<?php

namespace spec\Lunchbot\Persistent;

use Everzet\PersistedObjects\AccessorObjectIdentifier;
use Everzet\PersistedObjects\InMemoryRepository;
use Lunchbot\Entity\Member;
use Lunchbot\ValueObject\Member\MemberId;
use Lunchbot\ValueObject\Member\Name;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MembersInMemorySpec extends ObjectBehavior
{
    function it_stores_the_member_and_it_can_be_retrieved()
    {
        $this->beConstructedWith(new InMemoryRepository(
            new AccessorObjectIdentifier('getName')
        ));

        $member = Member::add(new MemberId('anya'), new Name('Anya'));

        $this->add($member);

        $this->all()->shouldReturn([$member]);
    }
}
