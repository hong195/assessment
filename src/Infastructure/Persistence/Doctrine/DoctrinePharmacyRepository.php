<?php


namespace Infastructure\Persistence\Doctrine;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Domain\Model\Pharmacy\Pharmacy;
use Domain\Model\Pharmacy\PharmacyId;
use Domain\Model\Pharmacy\PharmacyNumber;
use Domain\Model\Pharmacy\PharmacyRepository;

class DoctrinePharmacyRepository implements PharmacyRepository
{
    private EntityManagerInterface $em;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Pharmacy::class);
        $this->em = $em;
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
