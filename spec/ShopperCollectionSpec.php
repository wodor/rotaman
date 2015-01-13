<?php

namespace spec\RgpJones\Lunchbot;

use PhpSpec\ObjectBehavior;

class ShopperCollectionSpec extends ObjectBehavior
{
    function it_should_return_next_shopper()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris', 'Dave']);
        $this->setCurrentShopper('Alice');
        $this->next()->shouldBeLike('Bob');
        $this->next()->shouldBeLike('Chris');
        $this->next()->shouldBeLike('Dave');
        $this->next()->shouldBeLike('Alice');
    }

    function it_should_return_prev_shopper()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris', 'Dave']);
        $this->setCurrentShopper('Alice');
        $this->prev()->shouldBeLike('Dave');
        $this->prev()->shouldBeLike('Chris');
        $this->prev()->shouldBeLike('Bob');
        $this->prev()->shouldBeLike('Alice');
    }

    function it_returns_first_shopper_when_no_current_shopper()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris', 'Dave']);
        $this->next()->shouldBeLike('Alice');
    }

    function it_adds_new_shopper_to_list()
    {
        $this->beConstructedWith(['Alice', 'Bob']);
        $this->setCurrentShopper('Bob');
        $this->addShopper('Chris');
        $this->getShoppers()->shouldBeLike(['Alice', 'Bob', 'Chris']);
    }
}
