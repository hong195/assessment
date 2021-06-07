<?php


namespace Infastructure\Services;


use Doctrine\ORM\EntityManagerInterface;
use Domain\Exceptions\NotFoundEntityException;
use Domain\Model\Pharmacy\Email;
use Domain\Model\Pharmacy\Pharmacy;
use Domain\Model\Pharmacy\PharmacyId;
use Domain\Model\Pharmacy\PharmacyNumber;
use Domain\Model\Pharmacy\PharmacyRepository;
use Infastructure\Exceptions\PharmacyNumberHasBeenAlreadyTakenException;

class PharmacyService
{
    private PharmacyRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(PharmacyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @throws PharmacyNumberHasBeenAlreadyTakenException
     */
    public function addPharmacy(PharmacyId $pharmacyId, PharmacyNumber $number, Email $email) : void
    {
        $pharmacies = $this->repository->findByNumber($number);

        if (!$pharmacies->isEmpty())  {
            throw new PharmacyNumberHasBeenAlreadyTakenException;
        }

        $pharmacy = new Pharmacy($pharmacyId, $number, $email);
        $this->repository->add($pharmacy);
        $this->em->flush();
    }

    public function updatePharmacy(PharmacyId $pharmacyId, PharmacyNumber $number, Email $email) : void
    {
        $pharmacies = $this->repository->findByNumber($number);
        $pharmacy = $this->repository->findById($pharmacyId);

        if (!$pharmacies->contains($pharmacy))  {
            throw new PharmacyNumberHasBeenAlreadyTakenException;
        }

        $pharmacy->changeNumber($number);
        $pharmacy->changeEmail($email);

        $this->em->persist($pharmacy);
        $this->em->flush();
    }

    /**
     * @throws NotFoundEntityException
     */
    public function deletePharmacy(PharmacyId $pharmacyId) : void
    {
        $pharmacy = $this->repository->findById($pharmacyId);

        if (!$pharmacy) {
            throw new NotFoundEntityException;
        }

        $this->repository->remove($pharmacy);
        $this->em->flush();
    }
}
