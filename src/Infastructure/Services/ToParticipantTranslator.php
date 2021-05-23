<?php


namespace Infastructure\Services;


use Domain\Model\Employee\Employee;
use Domain\Model\Employee\Name;
use Domain\Model\Employee\ParticipantService;
use Domain\Model\Employee\Reviewer;
use Domain\Model\Employee\SalesManger;
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
