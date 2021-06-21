<?php


namespace App\Http\DataTransferObjects;


class PharmacyDto
{
    private string $pharmacyNumber;
    private string $email;

    public function __construct(string $pharmacyNumber, string $email)
    {
        $this->pharmacyNumber = $pharmacyNumber;
        $this->email = $email;
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
}
