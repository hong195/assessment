<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Model\Employee\Employee;
use App\Domain\Model\SaleManager\SaleManager;
use App\Domain\Model\SaleManager\SaleManagerRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineSaleManagerRepository extends AbstractRepository implements SaleManagerRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em,SaleManager::class);
    }

    public function findPharmacies(array $pharmacyIds)
    {
        $qb = $this->repository->createQueryBuilder('p');

        $query = $qb->select('s')
            ->add('where', $qb->expr()->in('e.pharmacy', $pharmacyIds))
            ->getQuery();

        return $query->getResult();
    }

    public function getAll(): array
    {
        return $this->repository->findAll();
    }
}
