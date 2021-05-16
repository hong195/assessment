<?php


namespace Domain\Model\Participant;


use Domain\Model\Pharmacy\PharmacyId;

final class Employee extends AbstractParticipant
{
    private PharmacyId $pharmacyId;

    public function __construct($identity, Name $name, PharmacyId $pharmacyId)
    {
        parent::__construct($identity, $name);
        $this->pharmacyId = $pharmacyId;
    }

    public function getPharmacyId(): PharmacyId
    {
        return $this->pharmacyId;
    }
}
