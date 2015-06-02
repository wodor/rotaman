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
            ['members' => $members, 'cancelledDates' => [], 'rota' => [], 'paymentCalendar' => []]
        )->willReturn(null);

        $this->beConstructedWith($storage, new \DateTime());

        $this->addShopper('Alice');
        $this->addShopper('Bob');
        $this->addShopper('Chris');
        $this->addShopper('Dave');

        $this->getShoppers()->shouldReturn($members);
    }

    function it_removes_members(Storage $storage)
    {
        $storage->load()->willReturn(['members' => ['Alice', 'Bob', 'Chris', 'Dave']]);
        $storage->save(
            ['members' => ['Alice', 'Chris', 'Dave'], 'cancelledDates' => [], 'rota' => [], 'paymentCalendar' => []]
        )->willReturn(null);

        $this->beConstructedWith($storage, new \DateTime());

        $this->removeShopper('Bob')->shouldReturn(null);

        $this->getShoppers()->shouldReturn(['Alice', 'Chris', 'Dave']);
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
            ['members' => $members, 'cancelledDates' => [], 'rota' => $expectedRota, 'paymentCalendar' => []]
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
            [
                'members' => ['Bob', 'Chris', 'Dave', 'Elaine', 'Alice'],
                'cancelledDates' => [],
                'rota' => ($currentRota + $expectedRota),
                'paymentCalendar' => []
            ]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->generateRota(new \DateTime('2010-01-14'), 5)->shouldReturn($expectedRota);
    }

    function it_excludes_members_removed_from_lunchclub(Storage $storage)
    {
        $currentRota = [
            '2010-01-01' => 'Bob',
            '2010-01-04' => 'Chris',
            '2010-01-05' => 'Dave',
            '2010-01-06' => 'Chris',
            '2010-01-07' => 'Dave',
            '2010-01-08' => 'Bob',
            '2010-01-11' => 'Elaine',
            '2010-01-12' => 'Chris',
            '2010-01-13' => 'Dave',
        ];
        $expectedRota = [
            '2010-01-14' => 'Alice',
            '2010-01-15' => 'Bob',
            '2010-01-18' => 'Chris',
            '2010-01-19' => 'Dave',
            '2010-01-20' => 'Alice',
        ];

        $storage->load()->willReturn(['members' => ['Bob', 'Chris', 'Dave'], 'rota' => $currentRota]);
        $storage->save(
            [
                'members' => ['Bob', 'Chris', 'Dave', 'Alice'],
                'cancelledDates' => [],
                'rota' => ($currentRota + $expectedRota),
                'paymentCalendar' => []
            ]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->addShopper('Alice');

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
            [
                'members' => $members,
                'cancelledDates' => [],
                'rota' => $expectedRota,
                'paymentCalendar' => []
            ]
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
            ['members' => $members, 'cancelledDates' => [], 'rota' => $expectedRota, 'paymentCalendar' => []]
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
            [
                'members' => $members,
                'cancelledDates' => ['2010-01-05'],
                'rota' => $expectedRota,
                'paymentCalendar' => []
            ]
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
            [
                'members' => ['Bob', 'Dave', 'Alice', 'Chris'],
                'cancelledDates' => [],
                'rota' => $currentRota + $nextRota,
                'paymentCalendar' => []
            ]
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
            'rota' => $updatedRota,
            'paymentCalendar' => []
        ])->willReturn(null);

        $this->beConstructedWith($storage);

        $this->swapShopperByDate(
            new \DateTime('2010-01-05'),
            new \DateTime('2010-01-01')
        )->shouldReturn($updatedRota);
    }

    function it_marks_shopper_as_paid(Storage $storage)
    {
        $storage->load()->willReturn([
            'paymentCalendar' => [
                '2010-03' => [
                    'Alice' => (float) 20.00,
                ]
            ]
        ]);

        $storage->save([
            'members' => [],
            'cancelledDates' => [],
            'rota' => [],
            'paymentCalendar' => [
                '2010-03' => [
                    'Alice' => (float) 20.00,
                    'Bob' => (float) 20.00,
                ]
            ]
        ])->willReturn(null);

        $this->beConstructedWith($storage);

        $this->shopperPaidForDate(
            new \DateTime('2010-03-22'),
            'Bob',
            (float) 20.00
        )->shouldReturn(true);
    }

    function it_returns_amount_shopper_as_paid(Storage $storage)
    {
        $storage->load()->willReturn([
            'paymentCalendar' => [
                '2010-03' => [
                    'Alice' => (float) 20.00,
                ]
            ]
        ]);

        $storage->save([
            'members' => [],
            'cancelledDates' => [],
            'rota' => [],
            'paymentCalendar' => [
                '2010-03' => [
                    'Alice' => (float) 20.00,
                ]
            ]
        ])->willReturn(null);

        $this->beConstructedWith($storage);

        $this->getAmountShopperPaidForDate(
            new \DateTime('2010-03-16'),
            'Alice'
        )->shouldReturn(20.00);
    }

    function it_returns_who_paid_for_date(Storage $storage)
    {
        $storage->load()->willReturn([
            'paymentCalendar' => [
                '2010-03' => [
                    'Alice' => (float) 20.00,
                ]
            ]
        ]);

        $storage->save([
            'members' => [],
            'cancelledDates' => [],
            'rota' => [],
            'paymentCalendar' => [
                '2010-03' => [
                    'Alice' => (float) 20.00,
                ]
            ]
        ])->willReturn(null);

        $this->beConstructedWith($storage);

        $this->getWhoPaidForDate(new \DateTime('2010-03-16'))->shouldReturn(['Alice']);
    }
}
