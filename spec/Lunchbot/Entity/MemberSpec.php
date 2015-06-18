<?php

namespace spec\Lunchbot\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MemberSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Lunchbot\Entity\Member');
    }
}
