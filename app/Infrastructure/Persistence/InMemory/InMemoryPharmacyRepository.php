<?php


namespace App\Infrastructure\Persistence\InMemory;


use App\Exceptions\NotFoundEntityException;
use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Model\Pharmacy\Pharmacy;
use App\Domain\Model\Pharmacy\PharmacyId;
use App\Domain\Model\Pharmacy\PharmacyNumber;
use App\Domain\Model\Pharmacy\PharmacyRepository;

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

    public function findOrFail($id): mixed
    {
        // TODO: Implement findOrFail() method.
    }
}
