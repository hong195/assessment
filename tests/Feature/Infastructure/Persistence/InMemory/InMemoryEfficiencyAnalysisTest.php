<?php

namespace Tests\Feature\Infastructure\Persistence\InMemory;

use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisRepository;
use Infastructure\Persistence\InMemory\InMemoryEfficiencyAnalysisRepository;
use Tests\Feature\Infastructure\Persistence\AbstractEfficiencyAnalysisTest;

class InMemoryEfficiencyAnalysisTest extends AbstractEfficiencyAnalysisTest
{
    public function getRepository() : EfficiencyAnalysisRepository
    {
        return new InMemoryEfficiencyAnalysisRepository();
    }
}
