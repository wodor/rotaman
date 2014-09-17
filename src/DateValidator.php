<?php
namespace RgpJones\Lunchbot;

use DateTime;
use DateInterval;

class DateValidator
{
    private $cancelledDates;

    private $interval;

    public function __construct(array $cancelledDates = [])
    {
        $this->cancelledDates = $cancelledDates;
        $this->interval = new DateInterval('P1D');
    }

    public function getCancelledDates()
    {
        return $this->cancelledDates;
    }

    public function getNextValidDate(DateTime $date)
    {
        while (in_array($date->format('l'), array('Saturday', 'Sunday'))
            || in_array($date->format('Y-m-d'), $this->cancelledDates)) {
            $date->add($this->interval);
        }

        return $date;
    }
}
