<?php

namespace spec\RgpJones\Lunchbot;

use PhpSpec\ObjectBehavior;
use RgpJones\Lunchbot\Storage;

class RotaManagerSpec extends ObjectBehavior
{
    function it_returns_clubbers(Storage $storage)
    {
        $clubbers = ['Alice', 'Bob', 'Chris', 'Dave'];

        $storage->load()->willReturn([]);
        $storage->save(
            ['clubbers' => $clubbers, 'rota' => []]
        )->willReturn(null);

        $this->beConstructedWith($storage, new \DateTime());

        $this->addShopper('Alice');
        $this->addShopper('Bob');
        $this->addShopper('Chris');
        $this->addShopper('Dave');
        $this->getShoppers()->shouldReturn($clubbers);
    }

    function it_returns_rota(Storage $storage)
    {
        $clubbers = ['Alice', 'Bob', 'Chris', 'Dave'];
        $expectedRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris'
        ];

        $storage->load()->willReturn(['clubbers' => $clubbers]);
        $storage->save(
            ['clubbers' => $clubbers, 'rota' => $expectedRota]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->generateRota(new \DateTime('2010-01-01'), 3)->shouldReturn($expectedRota);
    }

    function it_returns_shopper_for_date(Storage $storage)
    {
        $clubbers = ['Alice', 'Bob', 'Chris', 'Dave'];
        $expectedRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris'
        ];

        $storage->load()->willReturn(['clubbers' => $clubbers, 'rota' => $expectedRota]);
        $storage->save(
            ['clubbers' => $clubbers, 'rota' => $expectedRota]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->getShopperForDate(new \DateTime('2010-01-04'))->shouldReturn('Bob');
    }

    function it_skips_shopper_for_date(Storage $storage)
    {
        $clubbers = ['Alice', 'Bob', 'Chris', 'Dave'];
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

        $storage->load()->willReturn(['clubbers' => $clubbers, 'rota' => $currentRota]);
        $storage->save(
            ['clubbers' => $clubbers, 'rota' => $expectedRota]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->skipShopperForDate(new \DateTime('2010-01-05'));
    }
}
