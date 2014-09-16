<?php

namespace spec\RgpJones\Lunchbot;

use PhpSpec\ObjectBehavior;

class ShopperSpec extends ObjectBehavior
{

    function it_should_return_next_shopper()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris', 'Dave'], 'Alice');
        $this->next()->shouldReturn('Bob');
        $this->next()->shouldReturn('Chris');
        $this->next()->shouldReturn('Dave');
        $this->next()->shouldReturn('Alice');
    }

    function it_should_return_prev_shopper()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris', 'Dave'], 'Alice');
        $this->prev()->shouldReturn('Dave');
        $this->prev()->shouldReturn('Chris');
        $this->prev()->shouldReturn('Bob');
        $this->prev()->shouldReturn('Alice');
    }

    function it_returns_first_shopper_when_no_current_shopper()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris', 'Dave']);
        $this->next()->shouldReturn('Alice');
    }

    function it_adds_new_shopper_to_list()
    {
        $this->beConstructedWith(['Alice', 'Bob'], 'Bob');
        $this->addShopper('Chris');
        $this->getShoppers()->shouldReturn(['Alice', 'Bob', 'Chris']);
    }
}
