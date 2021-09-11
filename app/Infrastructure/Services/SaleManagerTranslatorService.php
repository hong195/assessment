<?php

namespace App\Infrastructure\Services;

use App\Domain\Model\SaleManager\SaleManagerRepository;
use App\Domain\Model\SaleManager\SaleManagerTranslator;

class SaleManagerTranslatorService
{
    private SaleManagerTranslator $translator;
    private SaleManagerRepository $repository;

    public function __construct(SaleManagerTranslator $translator, SaleManagerRepository $repository)
    {
        $this->translator = $translator;
        $this->repository = $repository;
    }
}
