<?php

namespace Tests\Feature\Infastructure\Persistence\InMemory;

use App\Domain\Model\Criterion\CriterionRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryCriterionRepository;
use Tests\Feature\Infastructure\Persistence\AbstractCriterionTest;

class InMemoryCriterionTest extends AbstractCriterionTest
{
    protected function getRepository(): CriterionRepository
    {
        return new InMemoryCriterionRepository();
    }
}
