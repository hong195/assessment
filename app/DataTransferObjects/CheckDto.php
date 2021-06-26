<?php


namespace App\DataTransferObjects;


class CheckDto
{
    private \DateTime $serviceDate;
    private int $amount;
    /**
     * @var float|int
     */
    private $saleConversion;

    public function __construct(\DateTime $serviceDate, int $amount = 0, float $saleConversion = 0)
    {
        $this->serviceDate = $serviceDate;
        $this->amount = $amount;
        $this->saleConversion = $saleConversion;
    }

    /**
     * @return \DateTime
     */
    public function getServiceDate(): \DateTime
    {
        return $this->serviceDate;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return float|int
     */
    public function getSaleConversion()
    {
        return $this->saleConversion;
    }
}
