<?php


namespace Infastructure\Persistence\InMemory;


use Doctrine\Common\Collections\ArrayCollection;
use Domain\Model\Pharmacy\Pharmacy;
use Domain\Model\Pharmacy\PharmacyId;
use Domain\Model\Pharmacy\PharmacyNumber;
use Domain\Model\Pharmacy\PharmacyRepository;

class InMemoryPharmacyRepository implements PharmacyRepository
{
    private ArrayCollection $pharmacies;

    public function __construct()
    {
        $this->pharmacies = new ArrayCollection([]);
    }

    public function all(): ArrayCollection
    {
        return $this->pharmacies;
    }

    public function findById(PharmacyId $pharmacyId): ?Pharmacy
    {
        $collection = $this->pharmacies->filter(function ($pharmacy) use ($pharmacyId) {
            if ($pharmacyId->isEqual($pharmacy->getId())) {
                return $pharmacy;
            }
            return null;
        });

        return $collection->isEmpty() ? null : $collection->first();
    }

    public function add(Pharmacy $pharmacy): void
    {
        $this->pharmacies->add($pharmacy);
    }

    public function remove(Pharmacy $pharmacy) : void
    {
        foreach ($this->pharmacies as $key => $pharmacy2) {
            if ($pharmacy->getId()->isEqual($pharmacy2->getId())) {
                unset($this->pharmacies[$key]);
                break;
            }
        }
    }

    public function findByNumber(PharmacyNumber $number): ArrayCollection
    {
        return $this->pharmacies->filter(function (Pharmacy $pharmacy) use ($number){
            return (string) $pharmacy->getNumber() === (string) $number;
        });
    }
}
