<?php

namespace spec\Lunchbot\Model\Member;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MemberAddedSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Lunchbot\Model\Member\MemberAdded');
    }
}
