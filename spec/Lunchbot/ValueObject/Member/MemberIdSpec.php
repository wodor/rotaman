<?php

namespace spec\Lunchbot\ValueObject\Member;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MemberIdSpec extends ObjectBehavior
{
    function it_returns_the_member_id()
    {
        $this->beConstructedWith('arnold');
        $this->__toString()->shouldReturn('arnold');
    }
}
