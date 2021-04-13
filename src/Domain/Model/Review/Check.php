<?php


namespace Domain\Model\Review;

class Check
{
    private ServiceDate $serviceDate;

    private int $amount;

    private float $saleConversion;

    public function __construct(ServiceDate $serviceDate, int $amount = 0, float $saleConversion = 0)
    {
        $this->serviceDate = $serviceDate;
        $this->amount = $amount;
        $this->saleConversion = $saleConversion;
    }

    public function getServiceDate(): ServiceDate
    {
        return $this->serviceDate;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getSaleConversion(): float
    {
        return $this->saleConversion;
    }
}
