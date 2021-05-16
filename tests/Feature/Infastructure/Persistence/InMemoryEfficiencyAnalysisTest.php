<?php

namespace Tests\Feature\Infastructure\Persistence;

use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisRepository;
use Infastructure\Persistence\InMemoryEfficiencyAnalysisRepository;

class InMemoryEfficiencyAnalysisTest extends AbstractEfficiencyAnalysisTest
{
    public function getRepository() : EfficiencyAnalysisRepository
    {
        return new InMemoryEfficiencyAnalysisRepository();
    }
}
