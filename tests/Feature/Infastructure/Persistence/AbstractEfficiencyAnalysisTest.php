<?php

namespace Tests\Feature\Infastructure\Persistence;

use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysis;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisRepository;
use Domain\Model\EfficiencyAnalysis\Month;
use Domain\Model\User\UserId;
use Tests\Unit\Domain\Model\Builders\EfficiencyAnalysisBuilder;
use Tests\TestCase;

abstract class AbstractEfficiencyAnalysisTest extends TestCase
{
    private EfficiencyAnalysisRepository $repository;

    abstract public function getRepository() : EfficiencyAnalysisRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getRepository();
    }

    public function test_find_by_id()
    {
        $analysis = EfficiencyAnalysisBuilder::anAnalysis()->build();
        $this->repository->add($analysis);
        $founded = $this->repository->findById($analysis->getId());

        $this->assertCount(1, $this->repository->all());
        $this->assertInstanceOf(EfficiencyAnalysis::class, $founded);
        $this->assertSame($analysis, $founded);
    }

    public function test_find_by_employee_id()
    {
        $analysis = EfficiencyAnalysisBuilder::anAnalysis()->build();
        $employeeId = new UserId($analysis->getEmployeeId());

        $this->repository->add($analysis);
        $founded = $this->repository->findByEmployeeId($employeeId);

        $this->assertSame((string) $employeeId, (string) $founded->last()->getEmployeeId());
    }

    public function test_find_by_month()
    {
        $analysis = EfficiencyAnalysisBuilder::anAnalysis()->withMonth(new Month(new \DateTime('2020-2-10')))->build();
        $analysisC = EfficiencyAnalysisBuilder::anAnalysis()->withMonth(new Month(new \DateTime('2020-2-12')))->build();
        $analysisB = EfficiencyAnalysisBuilder::anAnalysis()->withMonth(new Month(new \DateTime('2021-3-10')))->build();

        $this->repository->add($analysis);
        $this->repository->add($analysisC);
        $this->repository->add($analysisB);

        $founded = $this->repository->findByMonth(new Month(new \DateTime('2020-2-12')));

        $this->assertContains($analysis, $founded);
        $this->assertContains($analysisC, $founded);
        $this->assertNotContains($analysisB, $founded);
    }


    public function test_find_by_employees_ids()
    {
        $analysis = EfficiencyAnalysisBuilder::anAnalysis()->withMonth(new Month(new \DateTime('2020-2-10')))->build();
        $analysisC = EfficiencyAnalysisBuilder::anAnalysis()->withMonth(new Month(new \DateTime('2020-2-12')))->build();
        $analysisB = EfficiencyAnalysisBuilder::anAnalysis()->withMonth(new Month(new \DateTime('2021-3-10')))->build();
        $analysisD = EfficiencyAnalysisBuilder::anAnalysis()->withMonth(new Month(new \DateTime('2021-3-12')))->build();

        $this->repository->add($analysis);
        $this->repository->add($analysisC);
        $this->repository->add($analysisB);

        $idA = new UserId($analysis->getEmployeeId());
        $idB = new UserId($analysisC->getEmployeeId());
        $idC = new UserId($analysisB->getEmployeeId());

        $foundAnalysis = $this->repository->findByEmployeeIds([$idA, $idB,$idC]);

        $foundEmployeeIds = $foundAnalysis->map(function (EfficiencyAnalysis $singleAnalysis) {
            return $singleAnalysis->getEmployeeId();
        });

        $this->assertNotEmpty($foundAnalysis);
        $this->assertNotContains($analysisD->getEmployeeId(), $foundEmployeeIds);
        $this->assertContains($analysis->getEmployeeId(), $foundEmployeeIds);
        $this->assertContains($analysisC->getEmployeeId(), $foundEmployeeIds);
        $this->assertContains($analysisB->getEmployeeId(), $foundEmployeeIds);
    }
}
