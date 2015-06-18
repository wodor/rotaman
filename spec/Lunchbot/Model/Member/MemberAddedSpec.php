<?php

namespace spec\Lunchbot\Model\Member;

use Lunchbot\ValueObject\Member\MemberId;
use Lunchbot\ValueObject\Member\Name;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MemberAddedSpec extends ObjectBehavior
{
    function it_returns_member_id_and_name()
    {
        $memberId = new MemberId('angela');
        $name = new Name('Angela');
        $this->beConstructedWith($memberId, $name);
        $this->getMemberId()->shouldReturn($memberId);
        $this->getName()->shouldReturn($name);
    }
}
