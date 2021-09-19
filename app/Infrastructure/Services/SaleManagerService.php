<?php

namespace App\Infrastructure\Services;

use App\DataTransferObjects\SaleManagerPharmaciesDto;
use App\Domain\Model\Pharmacy\Pharmacy;
use App\Domain\Model\Pharmacy\PharmacyRepository;
use App\Domain\Model\SaleManager\SaleManager;
use App\Domain\Model\SaleManager\SaleManagerRepository;
use Doctrine\ORM\EntityManagerInterface;

class SaleManagerService
{
    private SaleManagerRepository $repository;
    private PharmacyRepository $pharmacyRepository;
    private EntityManagerInterface $em;

    public function __construct(SaleManagerRepository $repository,
                                PharmacyRepository $pharmacyRepository,
                                EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->pharmacyRepository = $pharmacyRepository;
        $this->em = $em;
    }

    /**
     * @throws \App\Exceptions\NotFoundEntityException
     * @throws \Exception
     */
    public function assingPharmacies(SaleManagerPharmaciesDto $dto)
    {
        $ids = $dto->getPharmciesIds();

        if (empty($ids)) {
            throw new \Exception('Pharmacies ids cannot be empty');
        }

        /** @var SaleManager $saleManager */
        $saleManager = $this->repository->findOrFail($dto->getSaleManagerId());

        $saleManager->deAssginPharmacies();

        $pharmacies = $this->pharmacyRepository->findByIds($ids);

        $saleManager->assignPharmacies($pharmacies->toArray());
        $this->em->persist($saleManager);
        $this->em->flush();
    }

    /**
     * @throws \App\Exceptions\NotFoundEntityException
     */
    public function deAssignPharmacies(SaleManagerPharmaciesDto $dto)
    {
        /** @var SaleManager $saleManager */
        $saleManager = $this->repository->findOrFail($dto->getSaleManagerId());

        $saleManager->deAssginPharmacies();
        $this->em->persist($saleManager);
        $this->em->flush();
    }

    /**
     * @return mixed
     */
    public function getSaleManagerList(): mixed
    {
        return $this->repository->getAll();
    }
}
