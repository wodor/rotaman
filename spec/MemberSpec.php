<?php

namespace spec\RgpJones\Lunchbot;

use PhpSpec\ObjectBehavior;

class MemberSpec extends ObjectBehavior
{
    function it_should_return_next_shopper()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris', 'Dave']);
        $this->next()->shouldReturn('Alice');
        $this->next()->shouldReturn('Bob');
        $this->next()->shouldReturn('Chris');
        $this->next()->shouldReturn('Dave');
    }

    function it_should_return_prev_shopper()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris', 'Dave']);
        $this->prev()->shouldReturn('Dave');
        $this->prev()->shouldReturn('Chris');
        $this->prev()->shouldReturn('Bob');
        $this->prev()->shouldReturn('Alice');
    }

    function it_adds_new_shopper_to_list()
    {
        $this->beConstructedWith(['Alice', 'Bob']);
        $this->addShopper('Chris');
        $this->getShoppers()->shouldReturn(['Alice', 'Bob', 'Chris']);
    }

    function it_removes_shopper_from_list()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris']);
        $this->removeShopper('Bob');
        $this->getShoppers()->shouldReturn(['Alice', 'Chris']);
    }

    function it_returns_shopper_after_name()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris', 'Dave', 'Elaine']);
        $this->getShopperAfter('Chris')->shouldReturn('Dave');
    }
}
