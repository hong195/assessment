<?php


namespace Domain\Model\Pharmacy;


use Domain\Model\Pharmacy\Exceptions\InvalidPharmacyNumberException;

final class PharmacyNumber
{
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
