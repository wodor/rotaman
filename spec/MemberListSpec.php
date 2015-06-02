<?php

namespace spec\RgpJones\Lunchbot;

use PhpSpec\ObjectBehavior;

class MemberListSpec extends ObjectBehavior
{
    function it_should_return_next_member()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris', 'Dave']);
        $this->next()->shouldReturn('Alice');
        $this->next()->shouldReturn('Bob');
        $this->next()->shouldReturn('Chris');
        $this->next()->shouldReturn('Dave');
    }

    function it_should_return_prev_member()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris', 'Dave']);
        $this->prev()->shouldReturn('Dave');
        $this->prev()->shouldReturn('Chris');
        $this->prev()->shouldReturn('Bob');
        $this->prev()->shouldReturn('Alice');
    }

    function it_adds_new_member_to_list()
    {
        $this->beConstructedWith(['Alice', 'Bob']);
        $this->addMember('Chris');
        $this->getMembers()->shouldReturn(['Alice', 'Bob', 'Chris']);
    }

    function it_removes_member_from_list()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris']);
        $this->removeMember('Bob');
        $this->getMembers()->shouldReturn(['Alice', 'Chris']);
    }

    function it_returns_member_after_name()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris', 'Dave', 'Elaine']);
        $this->getMemberAfter('Chris')->shouldReturn('Dave');
    }
}
