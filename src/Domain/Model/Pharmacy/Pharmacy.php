<?php


namespace Domain\Model\Pharmacy;


use Doctrine\Common\Collections\ArrayCollection;

final class Pharmacy
{
    private PharmacyId $pharmacyId;

    private Email $email;

    private ArrayCollection $employees;

    private PharmacyNumber $number;

    public function __construct(PharmacyId $pharmacyId, PharmacyNumber $number, Email $email)
    {
        $this->email = $email;
        $this->employees = new ArrayCollection([]);
        $this->pharmacyId = $pharmacyId;
        $this->number = $number;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function changeEmail(Email $email)
    {
        $this->email = $email;
    }

    public function changeNumber(PharmacyNumber $number)
    {
        $this->number = $number;
    }

    public function getId(): PharmacyId
    {
        return $this->pharmacyId;
    }

    public function getNumber(): PharmacyNumber
    {
        return $this->number;
    }
}
