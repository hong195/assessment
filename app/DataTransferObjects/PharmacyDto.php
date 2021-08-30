<?php


namespace App\DataTransferObjects;


class PharmacyDto
{
    private string $pharmacyNumber;
    private string $email;
    private ?string $address;

    public function __construct(string $pharmacyNumber, string $email, string $address = null)
    {
        $this->pharmacyNumber = $pharmacyNumber;
        $this->email = $email;
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getPharmacyNumber(): string
    {
        return $this->pharmacyNumber;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return ?string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }
}
