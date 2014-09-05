<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RotaManagerSpec extends ObjectBehavior
{
    function it_returns_clubbers(\Storage $storage)
    {
        $shoppers = ['Alice', 'Bob', 'Chris', 'Dave'];

        $storage->load()->willReturn([]);
        $storage->save(
            ['shoppers' => $shoppers, 'rota' => null]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->addClubber('Alice');
        $this->addClubber('Bob');
        $this->addClubber('Chris');
        $this->addClubber('Dave');
        $this->getClubbers()->shouldReturn($shoppers);
    }

    function it_returns_rota(\Storage $storage)
    {
        $shoppers = ['Alice', 'Bob', 'Chris', 'Dave'];
        $expectedRota =             [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris',
        ];

        $storage->load()->willReturn(['shoppers' => $shoppers]);
        $storage->save(
            ['shoppers' => $shoppers, 'rota' => $expectedRota]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->getRota(new \DateTime('2010-01-01'), 3)->shouldReturn($expectedRota);
    }

    function it_returns_shopper_for_date(\Storage $storage)
    {
        $shoppers = ['Alice', 'Bob', 'Chris', 'Dave'];
        $expectedRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris',
        ];

        $storage->load()->willReturn(['shoppers' => $shoppers, 'rota' => $expectedRota]);
        $storage->save(
            ['shoppers' => $shoppers, 'rota' => $expectedRota]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->getShopperForDate(new \DateTime('2010-01-05'))->shouldReturn('Chris');
    }
}
