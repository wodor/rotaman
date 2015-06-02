<?php
namespace RgpJones\Lunchbot;

use DateInterval;
use DateTime;
use InvalidArgumentException;

class Rota
{
    private $memberList;

    private $interval;

    private $currentRota;

    private $dateValidator;

    public function __construct(MemberList $memberList, DateValidator $dateValidator, array $currentRota = array())
    {
        $this->memberList = $memberList;
        $this->interval = new DateInterval('P1D');
        $this->dateValidator = $dateValidator;
        $this->currentRota = $currentRota;
    }

    public function generate(DateTime $date, $days)
    {
        $rota[$this->getDateKey($date)] = $this->getNextMember($date);
        while (count($rota) < $days) {
            $date = $date->add($this->interval);
            $rota[$this->getDateKey($date)] = $this->getNextMember($date);
        }
        $this->currentRota = array_merge($this->currentRota, $rota);

        return $rota;
    }

    public function getCurrentRota()
    {
        return $this->currentRota;
    }

    public function getMemberForDate(DateTime $date)
    {
        if (!isset($this->currentRota[$this->getDateKey($date)])) {
            $this->generate($date, 1);
        }

        return $this->currentRota[$this->getDateKey($date)];
    }

    public function skipMemberForDate(DateTime $date)
    {
        while (isset($this->currentRota[$this->getDateKey($date)])) {
            $this->currentRota[$this->getDateKey($date)] = $this->getMemberAfterDate($date);
            $date->add($this->interval);
        }
    }

    public function cancelOnDate(DateTime $cancelDate)
    {
        if ($this->dateValidator->isDateValid($cancelDate)) {
            $date = clone $cancelDate;
            if (isset($this->currentRota[$this->getDateKey($date)])) {
                $member = $this->currentRota[$this->getDateKey($date)];
                unset($this->currentRota[$this->getDateKey($date)]);
                while (isset($this->currentRota[$this->getDateKey($date->add($this->interval))])) {
                    $nextMember = $this->currentRota[$this->getDateKey($date)];
                    $this->currentRota[$this->getDateKey($date)] = $member;
                    $member = $nextMember;
                }
                $this->currentRota[$this->getDateKey($date)] = $member;
            }
            $this->dateValidator->addCancelledDate($cancelDate);

            return true;
        }

        return false;
    }


    public function getNextMember()
    {
        return $this->memberList->next();
    }

    public function getPreviousMember(DateTime $date)
    {
        $previousMember = null;
        if (isset($this->currentRota[$this->getDateKey($date->sub($this->interval))])) {
            $previousMember = $this->currentRota[$this->getDateKey($date->sub($this->interval))];
        }

        return $previousMember;
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

    public function swapMemberByDate(DateTime $toDate, DateTime $fromDate)
    {
        if (!isset($this->currentRota[$fromDate->format('Y-m-d')])) {
            throw new InvalidArgumentException('Specified From date ' . $fromDate->format('Y-m-d') . ' is invalid');
        }

        if (!isset($this->currentRota[$toDate->format('Y-m-d')])) {
            throw new InvalidArgumentException('Specified To date ' . $toDate->format('Y-m-d') . ' is invalid');
        }

        $fromMember = $this->currentRota[$fromDate->format('Y-m-d')];
        $toMember = $this->currentRota[$toDate->format('Y-m-d')];

        $this->currentRota[$fromDate->format('Y-m-d')] = $toMember;
        $this->currentRota[$toDate->format('Y-m-d')] = $fromMember;

        return $this->currentRota;
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

    protected function getMemberAfterDate(DateTime $date)
    {
        return $this->memberList->getMemberAfter($this->currentRota[$this->getDateKey($date)]);
    }
}
