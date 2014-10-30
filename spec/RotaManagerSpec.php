<?php

namespace spec\RgpJones\Lunchbot;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RgpJones\Lunchbot\Storage;

class RotaManagerSpec extends ObjectBehavior
{
    function it_returns_members(Storage $storage)
    {
        $members = ['Alice', 'Bob', 'Chris', 'Dave'];

        $storage->load()->willReturn([]);
        $storage->save(
            ['members' => $members, 'cancelledDates' => [], 'rota' => []]
        )->willReturn(null);

        $this->beConstructedWith($storage, new \DateTime());

        $this->addShopper('Alice');
        $this->addShopper('Bob');
        $this->addShopper('Chris');
        $this->addShopper('Dave');
        $this->getShoppers()->shouldReturn($members);
    }

    function it_returns_rota(Storage $storage)
    {
        $members = ['Alice', 'Bob', 'Chris', 'Dave'];
        $expectedRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris'
        ];

        $storage->load()->willReturn(['members' => $members]);
        $storage->save(
            ['members' => $members, 'cancelledDates' => [], 'rota' => $expectedRota]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->generateRota(new \DateTime('2010-01-01'), 3)->shouldReturn($expectedRota);
    }

    function it_maintains_order_of_members_in_rota(Storage $storage)
    {
        $currentRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Dave',
            '2010-01-05' => 'Chris',
            '2010-01-06' => 'Alice',
            '2010-01-07' => 'Bob',
            '2010-01-08' => 'Chris',
            '2010-01-11' => 'Dave',
            '2010-01-12' => 'Elaine',
            '2010-01-13' => 'Alice',
        ];
        $expectedRota = [
            '2010-01-14' => 'Bob',
            '2010-01-15' => 'Chris',
            '2010-01-18' => 'Dave',
            '2010-01-19' => 'Elaine',
            '2010-01-20' => 'Alice',
        ];

        $storage->load()->willReturn(['members' => ['Alice', 'Bob', 'Chris', 'Dave', 'Elaine'], 'rota' => $currentRota]);
        $storage->save(
            ['members' => ['Bob', 'Chris', 'Dave', 'Elaine', 'Alice'], 'cancelledDates' => [], 'rota' => ($currentRota + $expectedRota)]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->generateRota(new \DateTime('2010-01-14'), 5)->shouldReturn($expectedRota);
    }

    function it_returns_shopper_for_date(Storage $storage)
    {
        $members = ['Alice', 'Bob', 'Chris', 'Dave'];
        $expectedRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris'
        ];

        $storage->load()->willReturn(
            ['members' => $members, 'cancelledDates' => [], 'rota' => $expectedRota]
        );
        $storage->save(
            ['members' => $members, 'cancelledDates' => [], 'rota' => $expectedRota]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->getShopperForDate(new \DateTime('2010-01-04'))->shouldReturn('Bob');
    }

    function it_skips_shopper_for_date(Storage $storage)
    {
        $members = ['Alice', 'Bob', 'Chris', 'Dave'];
        $currentRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris',
            '2010-01-06' => 'Dave',
        ];
        $expectedRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Dave',
            '2010-01-06' => 'Alice',
        ];

        $storage->load()->willReturn(['members' => $members, 'cancelledDates' => [], 'rota' => $currentRota]);
        $storage->save(
            ['members' => $members, 'cancelledDates' => [], 'rota' => $expectedRota]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->skipShopperForDate(new \DateTime('2010-01-05'));
    }

    function it_cancels_lunchclub_on_date(Storage $storage)
    {
        $members = ['Alice', 'Bob', 'Chris', 'Dave'];
        $currentRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris',
            '2010-01-06' => 'Dave',
        ];
        $expectedRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-06' => 'Chris',
            '2010-01-07' => 'Dave',
        ];

        $storage->load()->willReturn(['members' => $members, 'cancelledDates' => [], 'rota' => $currentRota]);
        $storage->save(
            ['members' => $members, 'cancelledDates' => ['2010-01-05'], 'rota' => $expectedRota]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->cancelOnDate(new \DateTime('2010-01-05'));
    }

    function it_saves_shoppers_correctly_missing_from_current_rota(Storage $storage)
    {
        $members = ['Alice', 'Bob', 'Chris', 'Dave'];
        $currentRota = [
            '2009-12-29' => 'Bob',
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Dave',
            '2010-01-07' => 'Alice'
        ];
        $nextRota = [
            '2010-01-12' => 'Chris',
            '2010-01-13' => 'Bob',
            '2010-01-14' => 'Dave',
            '2010-01-15' => 'Alice',
        ];

        $storage->load()->willReturn(['members' => $members, 'cancelledDates' => [], 'rota' => $currentRota]);
        $storage->save(
            ['members' => ['Bob', 'Dave', 'Alice', 'Chris'], 'cancelledDates' => [], 'rota' => $currentRota + $nextRota]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->generateRota(new \DateTime('2010-01-12'), 4)->shouldReturn($nextRota);
    }

    function it_swaps_shoppers_on_dates_specified(Storage $storage)
    {
        $updatedRota = [
            '2010-01-01' => 'Chris',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Alice',
            '2010-01-06' => 'Dave',
        ];

        $storage->load()->willReturn([
            'members' => ['Alice', 'Bob', 'Chris', 'Dave'],
            'cancelledDates' => [],
            'rota' => [
                '2010-01-01' => 'Alice',
                '2010-01-04' => 'Bob',
                '2010-01-05' => 'Chris',
                '2010-01-06' => 'Dave'
            ]
        ]);
        $storage->save([
            'members' => ['Chris', 'Bob', 'Alice', 'Dave'],
            'cancelledDates' => [],
            'rota' => $updatedRota
        ])->willReturn(null);

        $this->beConstructedWith($storage);

        $this->swapShopperByDate(
            new \DateTime('2010-01-05'),
            new \DateTime('2010-01-01')
        )->shouldReturn($updatedRota);
    }
}
