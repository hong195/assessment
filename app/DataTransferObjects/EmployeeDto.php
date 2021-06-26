<?php


namespace App\DataTransferObjects;


class EmployeeDto
{
    private string $pharmacyId;
    private string $firstName;
    private string $lastName;
    private string $middleName;
    private \DateTime $birthdate;
    private string $gender;

    public function __construct(string $pharmacyId, string $firstName, string $lastName,
                                string $middleName, \DateTime $birthdate, string $gender)
    {
        $this->pharmacyId = $pharmacyId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->middleName = $middleName;
        $this->birthdate = $birthdate;
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @return string
     */
    public function getPharmacyId(): string
    {
        return $this->pharmacyId;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    /**
     * @return \DateTime
     */
    public function getBirthdate(): \DateTime
    {
        return $this->birthdate;
    }
}
