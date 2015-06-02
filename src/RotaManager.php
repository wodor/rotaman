<?php
namespace RgpJones\Lunchbot;

use DateTime;

class RotaManager
{
    private $storage;

    private $rota;

    private $dateValidator;

    private $member;

    private $paymentCalendar;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;

        $data = $storage->load();

        $currentRota = isset($data['rota']) ? $data['rota'] : [];
        $cancelledDates = isset($data['cancelledDates']) ? $data['cancelledDates'] : [];
        $members = isset($data['members']) ? $data['members'] : [];
        $paymentCalendar = isset($data['paymentCalendar']) ? $data['paymentCalendar'] : [];

        // Maintains members in order as they are in current rota
        $this->member = $this->getMemberList($members, $currentRota);
        $this->dateValidator = new DateValidator($cancelledDates);
        $this->rota = new Rota($this->member, $this->dateValidator, $currentRota);
        $this->paymentCalendar =  new PaymentCalendar($paymentCalendar);
    }

    public function __destruct()
    {
        if (!empty($this->storage)) {
            $this->storage->save([
                'members' => $this->member->getMembers(),
                'cancelledDates' => $this->dateValidator->getCancelledDates(),
                'rota' => $this->rota->getCurrentRota(),
                'paymentCalendar' => $this->paymentCalendar->getPaymentCalendar(),
            ]);
        }
    }

    public function addMember($name)
    {
        $this->member->addMember($name);
    }

    public function removeMember($name)
    {
        $this->member->removeMember($name);
    }

    public function getMembers()
    {
        return $this->member->getMembers();
    }

    public function generateRota(DateTime $date, $days)
    {
        return $this->rota->generate($date, $days);
    }

    public function getMemberForDate(DateTime $date)
    {
        return $this->rota->getMemberForDate($date);
    }

    public function skipMemberForDate(DateTime $date)
    {
        return $this->rota->skipMemberForDate($date);
    }

    public function cancelOnDate(DateTime $date)
    {
        return $this->rota->cancelOnDate($date);
    }

    public function swapMemberByDate(DateTime $toDate, DateTime $fromDate)
    {
        $rota = $this->rota->swapMemberByDate($toDate, $fromDate);
        $this->member = $this->getMemberList($this->member->getMembers(), $rota);
        return $rota;
    }

    public function getAmountMemberPaidForDate($date, $member)
    {
        return $this->paymentCalendar->getAmountMemberPaidForDate($date, $member);
    }

    public function memberPaidForDate(DateTime $date, $member, $amount)
    {
        return $this->paymentCalendar->memberPaidForDate($date, $member, $amount);
    }

    public function getWhoPaidForDate(DateTime $date)
    {
        return $this->paymentCalendar->getWhoPaidForDate($date);
    }



    protected function getMemberList($members, $rota)
    {
        return new MemberList($this->getMembersInRotaOrder($members, $rota));
    }

    protected function getMembersInRotaOrder(array $members, array $currentRota)
    {
        $reverseCurrentRota = $this->getMembersFromRotaInRecentOrder($currentRota);
        $allMembers = array_values(array_unique(array_merge($reverseCurrentRota, $members)));
        return array_values(array_intersect($allMembers, $members));
    }

    /**
     * Gets the members in the rota in the order that they were most recently
     *
     * @param $rota
     *
     * @return array
     */
    protected function getMembersFromRotaInRecentOrder($rota)
    {
        return array_reverse(array_unique(array_reverse($rota)));
    }
}
