<?php

namespace Tests\Feature\Infastructure\Persistence\InMemory;

use Domain\Model\Criterion\CriterionRepository;
use Infastructure\Persistence\InMemory\InMemoryCriterionRepository;
use Tests\Feature\Infastructure\Persistence\AbstractCriterionTest;

class InMemoryCriterionTest extends AbstractCriterionTest
{
    protected function getRepository(): CriterionRepository
    {
        return new InMemoryCriterionRepository();
    }
}
