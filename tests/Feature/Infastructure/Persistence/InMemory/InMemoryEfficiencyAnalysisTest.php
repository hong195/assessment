<?php

namespace Tests\Feature\Infastructure\Persistence\InMemory;

use App\Domain\Model\FinalGrade\FinalGradeRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryFinalGradeRepository;
use Tests\Feature\Infastructure\Persistence\AbstractEfficiencyAnalysisTest;

class InMemoryEfficiencyAnalysisTest extends AbstractEfficiencyAnalysisTest
{
    public function getRepository() : FinalGradeRepository
    {
        return new InMemoryFinalGradeRepository();
    }
}
