<?php
namespace RgpJones\Lunchbot;

use DateTime;

class PaymentCalendar
{
    /**
     * @var array
     */
    private $paymentCalendar;

    public function __construct(array $currentPaymentCalendar = [])
    {

        $this->paymentCalendar = $currentPaymentCalendar;
    }

    public function getPaymentCalendar()
    {
        return $this->paymentCalendar;
    }

    public function shopperPaidForDate(DateTime $date, $shopper, $amount)
    {
        if (isset($this->paymentCalendar[$this->getDateKey($date)][$shopper])) {
            $amount += $this->paymentCalendar[$this->getDateKey($date)][$shopper];
        }
        $this->paymentCalendar[$this->getDateKey($date)][$shopper] = $amount;
        return true;
    }

    public function getAmountShopperPaidForDate(DateTime $date, $shopper)
    {
        $amount = 0;
        if (isset($this->paymentCalendar[$this->getDateKey($date)][$shopper])) {
            $amount = $this->paymentCalendar[$this->getDateKey($date)][$shopper];
        }
        return $amount;
    }

    public function getWhoPaidForDate(DateTime $date)
    {
        return array_keys($this->paymentCalendar[$this->getDateKey($date)]);
    }

    protected function getDateKey(DateTime $date)
    {
        return $date->format('Y-m');
    }
}
