<?php


namespace Infastructure\Services;


use App\Http\DataTransferObjects\PharmacyDto;
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
     * @throws \Domain\Model\Pharmacy\Exceptions\InvalidPharmacyEmailException
     * @throws \Domain\Model\Pharmacy\Exceptions\InvalidPharmacyNumberException
     */
    public function addPharmacy(PharmacyDto $dto) : void
    {
        $number = new PharmacyNumber($dto->getPharmacyNumber());
        $pharmacies = $this->repository->findByNumber($number);

        $id = PharmacyId::next();
        $email = new Email($dto->getEmail());

        if (!$pharmacies->isEmpty())  {
            throw new PharmacyNumberHasBeenAlreadyTakenException;
        }

        $pharmacy = new Pharmacy($id, $number, $email);
        $this->repository->add($pharmacy);
        $this->em->flush();
    }

    /**
     * @throws \Domain\Model\Pharmacy\Exceptions\InvalidPharmacyNumberException
     * @throws PharmacyNumberHasBeenAlreadyTakenException
     * @throws \Domain\Model\Pharmacy\Exceptions\InvalidPharmacyEmailException
     */
    public function updatePharmacy(string $id, PharmacyDto $dto) : void
    {
        $number = new PharmacyNumber($dto->getPharmacyNumber());
        $pharmacyId = new PharmacyId($id);
        $email = new Email($dto->getEmail());

        $pharmacies = $this->repository->findByNumber($number);
        $pharmacy = $this->repository->findById($pharmacyId);

        if (!$pharmacies->contains($pharmacy) && !$pharmacies->isEmpty())  {
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
    public function deletePharmacy(string $pharmacyId) : void
    {
        $pharmacy = $this->repository->findOrFail($pharmacyId);

        if (!$pharmacy) {
            throw new NotFoundEntityException;
        }

        $this->repository->remove($pharmacy);
        $this->em->flush();
    }
}
