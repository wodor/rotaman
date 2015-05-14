<?php

namespace spec\RgpJones\Lunchbot;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RgpJones\Lunchbot\Shopper;
use RgpJones\Lunchbot\DateValidator;

class RotaSpec extends ObjectBehavior
{
    function it_generates_rota_for_next_5_days()
    {
        $this->beConstructedWith(
            new Shopper(['Alice', 'Bob', 'Chris', 'Dave']),
            new DateValidator()
        );

        $this->generate(new \DateTime('2010-01-11'), 5)->shouldReturn(
            [
                '2010-01-11' => 'Alice',
                '2010-01-12' => 'Bob',
                '2010-01-13' => 'Chris',
                '2010-01-14' => 'Dave',
                '2010-01-15' => 'Alice'
            ]
        );
    }

    function it_generates_rota_for_next_5_days_and_skip_weekend()
    {
        $this->beConstructedWith(
            new Shopper(['Alice', 'Bob', 'Chris', 'Dave']),
            new DateValidator()
        );

        $this->generate(new \DateTime('2010-01-01'), 5)->shouldReturn(
            [
                '2010-01-01' => 'Alice',
                '2010-01-04' => 'Bob',
                '2010-01-05' => 'Chris',
                '2010-01-06' => 'Dave',
                '2010-01-07' => 'Alice'
            ]
        );
    }

    function it_returns_shopper_for_date()
    {
        $currentRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris',
        ];

        $this->beConstructedWith(
            new Shopper(['Alice', 'Bob', 'Chris', 'Dave']),
            new DateValidator(),
            $currentRota
        );

        $this->getShopperForDate(new \DateTime('2010-01-05'))->shouldReturn('Chris');
    }

    function it_generates_rota_with_existing_rota()
    {
        $clubbers = ['Alice', 'Bob', 'Chris', 'Dave'];
        $currentRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris',
        ];
        $expectedRota = $currentRota + [
            '2010-01-06' => 'Dave',
            '2010-01-07' => 'Alice',
            '2010-01-08' => 'Bob',
            '2010-01-11' => 'Chris',
            '2010-01-12' => 'Dave',
            '2010-01-13' => 'Alice',
            '2010-01-14' => 'Bob',
        ];

        $this->beConstructedWith(new Shopper($clubbers), new DateValidator(), $currentRota);

        $this->generate(new \DateTime('2010-01-01'), 10)->shouldReturn($expectedRota);
    }

    function it_skips_current_user_and_realigns_rota()
    {
        $this->beConstructedWith(
            new Shopper(['Alice', 'Bob', 'Chris', 'Dave']),
            new DateValidator(),
            [
                '2010-01-01' => 'Alice',
                '2010-01-04' => 'Bob',
                '2010-01-05' => 'Chris',
                '2010-01-06' => 'Dave',
            ]
        );

        $this->skipShopperForDate(new \DateTime('2010-01-04'));
        $this->getCurrentRota()->shouldReturn([
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Chris',
            '2010-01-05' => 'Dave',
            '2010-01-06' => 'Alice',
        ]);
    }

    function it_cancels_lunchclub_on_date_and_realigns_rota()
    {
        $date = new \DateTime('2010-01-04');

        $this->beConstructedWith(
            new Shopper(['Alice', 'Bob', 'Chris', 'Dave']),
            new DateValidator(),
            [
                '2010-01-01' => 'Alice',
                '2010-01-04' => 'Bob',
                '2010-01-05' => 'Chris',
                '2010-01-06' => 'Dave',
            ]
        );

        $this->cancelOnDate($date);
        $this->getCurrentRota()->shouldReturn(
            [
                '2010-01-01' => 'Alice',
                '2010-01-05' => 'Bob',
                '2010-01-06' => 'Chris',
                '2010-01-07' => 'Dave',
            ]
        );
    }

    function it_gets_previous_rota_date()
    {
        $this->beConstructedWith(
            new Shopper(['Alice', 'Bob', 'Chris', 'Dave']),
            new DateValidator(),
            [
                '2010-01-01' => 'Alice',
                '2010-01-04' => 'Bob',
                '2010-01-05' => 'Chris',
                '2010-01-06' => 'Dave',
            ]
        );

        $this->getPreviousRotaDate(new \DateTime('2010-01-04'))->shouldBeLike(new \DateTime('2010-01-01'));
        $this->getPreviousRotaDate(new \DateTime('2012-01-01'))->shouldBeLike(new \DateTime('2010-01-06'));
    }

    function it_returns_previous_shopper()
    {
        $this->beConstructedWith(
            new Shopper(['Alice', 'Bob', 'Chris', 'Dave']),
            new DateValidator(),
            [
                '2010-01-01' => 'Alice',
                '2010-01-04' => 'Bob',
                '2010-01-05' => 'Chris',
                '2010-01-06' => 'Dave',
            ]
        );

        $this->getPreviousShopper(new \DateTime('2010-01-05'))->shouldReturn('Bob');
    }

    function it_swaps_shoppers()
    {
        $this->beConstructedWith(
            new Shopper(['Alice', 'Bob', 'Chris', 'Dave']),
            new DateValidator(),
            [
                '2010-01-01' => 'Alice',
                '2010-01-04' => 'Bob',
                '2010-01-05' => 'Chris',
                '2010-01-06' => 'Dave',
            ]
        );

        $this->swapShopperByDate(new \DateTime('2010-01-06'), new \DateTime('2010-01-04'))
            ->shouldReturn([
                '2010-01-01' => 'Alice',
                '2010-01-04' => 'Dave',
                '2010-01-05' => 'Chris',
                '2010-01-06' => 'Bob',
            ]);
    }
}
