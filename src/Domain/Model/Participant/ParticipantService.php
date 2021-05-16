<?php


namespace Domain\Model\Participant;


use Domain\Model\Pharmacy\PharmacyId;

interface ParticipantService
{
    public function toEmployee($identity, Name $name, PharmacyId $pharmacyId) : Employee;

    public function toReviewer($identity, Name $name) : Reviewer;

    public function toSalesManger($identity, Name $name) : SalesManger;
}
