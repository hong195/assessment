<?php


namespace Infastructure\Services;


use Domain\Model\Participant\Employee;
use Domain\Model\Participant\Name;
use Domain\Model\Participant\ParticipantService;
use Domain\Model\Participant\Reviewer;
use Domain\Model\Participant\SalesManger;
use Domain\Model\Pharmacy\PharmacyId;

final class ToParticipantTranslator implements ParticipantService
{
    public function toEmployee($identity, Name $name, PharmacyId $pharmacyId): Employee
    {
        return new Employee($identity, $name, $pharmacyId);
    }

    public function toReviewer($identity, Name $name): Reviewer
    {
        return new Reviewer($identity, $name);
    }

    public function toSalesManger($identity, Name $name): SalesManger
    {
        return new SalesManger($identity, $name);
    }
}
