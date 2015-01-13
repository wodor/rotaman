<?php
namespace RgpJones\Lunchbot;

use DateInterval;
use DateTime;
use InvalidArgumentException;

class Rota
{
    private $shoppers;

    private $interval;

    private $currentRota;

    private $dateValidator;

    public function __construct(ShopperCollection $shoppers, DateValidator $dateValidator, array $currentRota = array())
    {
        $this->shoppers = $shoppers;
        $this->interval = new DateInterval('P1D');
        $this->dateValidator = $dateValidator;
        $this->currentRota = $currentRota;
    }

    public function generate(DateTime $date, $days)
    {
        // Sets current shopper to the shopper for the previous time lunchclub ran
        $previousDate = $this->getPreviousRotaDate($date);
        if (!is_null($previousDate)) {
            $this->shoppers->setCurrentShopper($this->currentRota[$previousDate->format('Y-m-d')]);
        }

        $rota[$this->getDateKey($date)] = $this->getNextShopper($date);
        while (count($rota) < $days) {
            $date = $date->add($this->interval);
            $rota[$this->getDateKey($date)] = $this->getNextShopper($date);
        }
        $this->currentRota = array_merge($this->currentRota, $rota);

        return $rota;
    }

    public function getCurrentRota()
    {
        return $this->currentRota;
    }

    public function getShopperForDate(DateTime $date)
    {
        if (!isset($this->currentRota[$this->getDateKey($date)])) {
            $this->generate($date, 1);
        }

        return $this->currentRota[$this->getDateKey($date)];
    }

    public function skipShopperForDate(DateTime $date)
    {
        while (isset($this->currentRota[$this->getDateKey($date)])) {
            $currentDate = clone $date;
            $this->currentRota[$this->getDateKey($currentDate)] = $this->getNextShopper($date->add($this->interval));
        }
    }

    public function cancelOnDate(DateTime $cancelDate)
    {
        if ($this->dateValidator->isDateValid($cancelDate)) {
            $date = clone $cancelDate;
            if (isset($this->currentRota[$this->getDateKey($date)])) {
                $shopper = $this->currentRota[$this->getDateKey($date)];
                unset($this->currentRota[$this->getDateKey($date)]);
                while (isset($this->currentRota[$this->getDateKey($date->add($this->interval))])) {
                    $nextShopper = $this->currentRota[$this->getDateKey($date)];
                    $this->currentRota[$this->getDateKey($date)] = $shopper;
                    $shopper = $nextShopper;
                }
                $this->currentRota[$this->getDateKey($date)] = $shopper;
            }
            $this->dateValidator->addCancelledDate($cancelDate);

            return true;
        }

        return false;
    }

    public function getNextShopper(\DateTime $date)
    {
        if (isset($this->currentRota[$this->getDateKey($date)])) {
            $shopper = $this->currentRota[$this->getDateKey($date)];
            $this->shoppers->setCurrentShopper($this->currentRota[$this->getDateKey($date)]);

            return $shopper;
        } else {
            return $this->shoppers->next();
        }
    }

    public function getPreviousShopper(DateTime $date)
    {
        $previousShopper = null;
        if (isset($this->currentRota[$this->getDateKey($date->sub($this->interval))])) {
            $previousShopper = $this->currentRota[$this->getDateKey($date->sub($this->interval))];
        }

        return $previousShopper;
    }

    public function getPreviousRotaDate(DateTime $date)
    {
        $rotaDates = $this->getRotaDatesWithDate($date);

        $rotaDate = null;
        $offset = array_search($date->format('Y-m-d'), $rotaDates);
        if ($offset > 0) {
            $rotaDate = new DateTime($rotaDates[$offset-1]);
        }

        return $rotaDate;
    }

    protected function getRotaDatesWithDate(DateTime $date)
    {
        $date = $date->format('Y-m-d');
        $rotaDates = array_keys($this->currentRota);
        if (!in_array($date, $rotaDates)) {
            $rotaDates[] = $date;
        }
        sort($rotaDates);

        return $rotaDates;
    }

    protected function getDateKey(DateTime $date)
    {
        return $this->dateValidator->getNextValidDate($date)->format('Y-m-d');
    }

    public function swapShopperByDate(DateTime $toDate, DateTime $fromDate)
    {
        if (!isset($this->currentRota[$fromDate->format('Y-m-d')])) {
            throw new InvalidArgumentException('Specified From date ' . $fromDate->format('Y-m-d') . ' is invalid');
        }

        if (!isset($this->currentRota[$toDate->format('Y-m-d')])) {
            throw new InvalidArgumentException('Specified To date ' . $toDate->format('Y-m-d') . ' is invalid');
        }

        $fromShopper = $this->currentRota[$fromDate->format('Y-m-d')];
        $toShopper = $this->currentRota[$toDate->format('Y-m-d')];

        $this->currentRota[$fromDate->format('Y-m-d')] = $toShopper;
        $this->currentRota[$toDate->format('Y-m-d')] = $fromShopper;

        return $this->currentRota;
    }
}
