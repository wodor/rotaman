<?php
namespace spec\RgpJones\Lunchbot;

use PhpSpec\ObjectBehavior;

class ShopperSpec extends ObjectBehavior
{
    function it_returns_the_shopper_name_as_string()
    {
        $this->beConstructedWith('Bob');

        $this->__toString()->shouldReturn('Bob');
    }
}

