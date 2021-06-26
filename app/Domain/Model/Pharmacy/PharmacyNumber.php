<?php


namespace App\Domain\Model\Pharmacy;

use Doctrine\ORM\Mapping as ORM;
use App\Exceptions\InvalidPharmacyNumberException;

/**
 * @ORM\Embeddable
 */
final class PharmacyNumber
{
    /**
     * @ORM\Column  (type="string")
     */
    private string $number;

    /**
     * PharmacyNumber constructor.
     * @param string $number
     * @throws InvalidPharmacyNumberException
     */
    public function __construct(string $number)
    {
        $this->assertNotEmpty($number);
        $this->number = $number;
    }

    public function __toString() : string
    {
        return $this->number;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @throws InvalidPharmacyNumberException
     */
    public function assertNotEmpty(string $number)
    {
        if (empty($number)) {
            throw new InvalidPharmacyNumberException;
        }
    }
}
