<?php


namespace Domain\Model\Assessment;

final class Check
{
    private \DateTime $serviceDate;

    private int $amount;

    private float $saleConversion;

    public function __construct(\DateTime $serviceDate, int $amount = 0, float $saleConversion = 0)
    {
        $this->serviceDate = $serviceDate;
        $this->amount = $amount;
        $this->saleConversion = $saleConversion;
    }

    public function getServiceDate(): \DateTime
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
