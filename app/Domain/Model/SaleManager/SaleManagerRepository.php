<?php

namespace App\Domain\Model\SaleManager;

use App\Domain\Shared\AbstractRepository;

interface SaleManagerRepository extends AbstractRepository
{
    public function findPharmacies(array $pharmacyIds);

    public function getAll();
}
