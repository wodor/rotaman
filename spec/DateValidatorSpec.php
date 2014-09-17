<?php

namespace spec\RgpJones\Lunchbot;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RgpJones\Lunchbot\DateValidator;

class DateValidatorSpec extends ObjectBehavior
{
    function it_returns_specified_date_as_valid_date()
    {
        $date = new \DateTime('2010-01-01');
        $this->getNextValidDate($date)->shouldReturn($date);
    }

    function it_returns_monday_when_passed_weekend_date()
    {
        $this->getNextValidDate(new \DateTime('2010-01-02'))->shouldBeLike(new \DateTime('2010-01-04'));
        $this->getNextValidDate(new \DateTime('2010-01-03'))->shouldBeLike(new \DateTime('2010-01-04'));
    }

    function it_returns_date_after_cancelled_date()
    {
        $this->beConstructedWith(array('2010-01-05'));

        $this->getNextValidDate(new \DateTime('2010-01-05'))->shouldBeLike(new \DateTime('2010-01-06'));
    }
}
