<?php

namespace spec\Lunchbot\Application;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MemberAddedProjectorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Lunchbot\Application\MemberAddedProjector');
    }
}
