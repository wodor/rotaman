<?php

namespace spec\Lunchbot\ValueObject\Member;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NameSpec extends ObjectBehavior
{
    function it_returns_the_name_as_string()
    {
        $this->beConstructedWith('Albert');
        $this->__toString()->shouldReturn('Albert');
    }
}
