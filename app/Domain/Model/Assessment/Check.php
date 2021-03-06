<?php


namespace App\Domain\Model\Assessment;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class Check
 * @ORM\Embeddable
 */
final class Check
{
    /**
     * @ORM\Column (type="datetime")
     */
    private \DateTime $serviceDate;

    /**
     * @ORM\Column (type="integer")
     */
    private int $amount;
    /**
     * @ORM\Column (type="float")
     */
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
