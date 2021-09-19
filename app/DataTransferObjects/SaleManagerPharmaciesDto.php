<?php

namespace App\DataTransferObjects;

class SaleManagerPharmaciesDto
{
    private array $pharmciesIds = [];
    private ?int $saleManagerId;

    public function __construct(int $saleManagerId = null, array $pharmciesIds = [])
    {
        $this->pharmciesIds = $pharmciesIds;
        $this->saleManagerId = $saleManagerId;
    }

    /**
     * @return int
     */
    public function getSaleManagerId(): int
    {
        return $this->saleManagerId;
    }

    /**
     * @return array
     */
    public function getPharmciesIds(): array
    {
        return $this->pharmciesIds;
    }
}
