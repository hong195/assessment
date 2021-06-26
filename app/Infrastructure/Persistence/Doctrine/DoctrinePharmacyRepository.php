<?php


namespace App\Infrastructure\Persistence\Doctrine;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use App\Domain\Model\Pharmacy\Pharmacy;
use App\Domain\Model\Pharmacy\PharmacyId;
use App\Domain\Model\Pharmacy\PharmacyNumber;
use App\Domain\Model\Pharmacy\PharmacyRepository;

class DoctrinePharmacyRepository extends AbstractRepository implements PharmacyRepository
{

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, Pharmacy::class);
    }

    public function all(): ArrayCollection
    {
        return new ArrayCollection($this->repository->findAll());
    }

    public function findById(PharmacyId $pharmacyId): ?Pharmacy
    {
        return $this->repository->find($pharmacyId);
    }

    public function add(Pharmacy $pharmacy): void
    {
        $this->em->persist($pharmacy);
    }

    public function remove(Pharmacy $pharmacy)
    {
        $this->em->remove($pharmacy);
    }

    public function findByNumber(PharmacyNumber $number): ArrayCollection
    {
        return new ArrayCollection($this->repository->findBy(['number.number' => (string) $number]));
    }
}
