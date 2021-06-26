<?php


namespace App\Domain\Model\Pharmacy;


use Doctrine\Common\Collections\ArrayCollection;

interface PharmacyRepository
{
    public function all() : ArrayCollection;

    public function findById(PharmacyId $pharmacyId) : ?Pharmacy;

    public function add(Pharmacy $pharmacy) :void;

    public function remove(Pharmacy $pharmacy);

    public function findByNumber(PharmacyNumber $number);
}
