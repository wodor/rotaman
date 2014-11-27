<?php
namespace RgpJones\Lunchbot;

use DateTime;

class RotaManager
{
    private $storage;

    private $rota;

    private $dateValidator;

    private $shopper;

    private $paymentCalendar;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;

        $data = $storage->load();

        $currentRota = isset($data['rota']) ? $data['rota'] : [];
        $cancelledDates = isset($data['cancelledDates']) ? $data['cancelledDates'] : [];
        $members = isset($data['members']) ? $data['members'] : [];
        $paymentCalendar = isset($data['paymentCalendar']) ? $data['paymentCalendar'] : [];

        // Maintains shoppers in order as they are in current rota
        $this->shopper = $this->getShopperEntity($members, $currentRota);
        $this->dateValidator = new DateValidator($cancelledDates);
        $this->rota = new Rota($this->shopper, $this->dateValidator, $currentRota);
        $this->paymentCalendar =  new PaymentCalendar($paymentCalendar);
    }

    public function __destruct()
    {
        if (!empty($this->storage)) {
            $this->storage->save([
                'members' => $this->shopper->getShoppers(),
                'cancelledDates' => $this->dateValidator->getCancelledDates(),
                'rota' => $this->rota->getCurrentRota(),
                'paymentCalendar' => $this->paymentCalendar->getPaymentCalendar(),
            ]);
        }
    }

    public function addShopper($name)
    {
        $this->shopper->addShopper($name);
    }

    public function getShoppers()
    {
        return $this->shopper->getShoppers();
    }

    public function generateRota(DateTime $date, $days)
    {
        return $this->rota->generate($date, $days);
    }

    public function getShopperForDate(DateTime $date)
    {
        return $this->rota->getShopperForDate($date);
    }

    public function skipShopperForDate(DateTime $date)
    {
        return $this->rota->skipShopperForDate($date);
    }

    public function cancelOnDate(DateTime $date)
    {
        return $this->rota->cancelOnDate($date);
    }

    public function swapShopperByDate(DateTime $toDate, DateTime $fromDate)
    {
        $rota = $this->rota->swapShopperByDate($toDate, $fromDate);
        $this->shopper = $this->getShopperEntity($this->shopper->getShoppers(), $rota);
        return $rota;
    }

    public function getAmountShopperPaidForDate($date, $shopper)
    {
        return $this->paymentCalendar->getAmountShopperPaidForDate($date, $shopper);
    }

    public function shopperPaidForDate(DateTime $date, $shopper, $amount)
    {
        return $this->paymentCalendar->shopperPaidForDate($date, $shopper, $amount);
    }

    public function getWhoPaidForDate(DateTime $date)
    {
        return $this->paymentCalendar->getWhoPaidForDate($date);
    }



    protected function getShopperEntity($shoppers, $rota)
    {
        return new ShopperCollection($this->getMembersInRotaOrder($shoppers, $rota));
    }

    protected function getMembersInRotaOrder(array $members, array $currentRota)
    {
        $reverseCurrentRota = array_reverse(array_unique(array_reverse($currentRota)));
        return array_values(array_unique(array_merge($reverseCurrentRota, $members)));
    }
}
