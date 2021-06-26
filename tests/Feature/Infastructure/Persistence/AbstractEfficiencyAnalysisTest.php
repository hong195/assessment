<?php

namespace Tests\Feature\Infastructure\Persistence;

use App\Domain\Model\Employee\EmployeeId;
use App\Domain\Model\FinalGrade\FinalGrade;
use App\Domain\Model\FinalGrade\FinalGradeRepository;
use App\Domain\Model\FinalGrade\Month;
use App\Domain\Model\User\UserId;
use Tests\Builders\FinalGradeBuilder;
use Tests\TestCase;

abstract class AbstractEfficiencyAnalysisTest extends TestCase
{
    private FinalGradeRepository $repository;

    abstract public function getRepository() : FinalGradeRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getRepository();
    }

    public function test_find_by_id()
    {
        $analysis = FinalGradeBuilder::anAnalysis()->build();
        $this->repository->add($analysis);
        $founded = $this->repository->findById($analysis->getId());

        $this->assertCount(1, $this->repository->all());
        $this->assertInstanceOf(FinalGrade::class, $founded);
        $this->assertSame($analysis, $founded);
    }

    public function test_find_by_employee_id()
    {
        $analysis = FinalGradeBuilder::anAnalysis()->build();
        $employeeId = new EmployeeId($analysis->getEmployeeId());

        $this->repository->add($analysis);
        $founded = $this->repository->findByEmployeeId($employeeId);

        $this->assertSame((string) $employeeId, (string) $founded->last()->getEmployeeId());
    }

    public function test_find_by_month()
    {
        $analysis = FinalGradeBuilder::anAnalysis()->withMonth(new Month(new \DateTime('2020-2-10')))->build();
        $analysisC = FinalGradeBuilder::anAnalysis()->withMonth(new Month(new \DateTime('2020-2-12')))->build();
        $analysisB = FinalGradeBuilder::anAnalysis()->withMonth(new Month(new \DateTime('2021-3-10')))->build();

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
        $analysis = FinalGradeBuilder::anAnalysis()->withMonth(new Month(new \DateTime('2020-2-10')))->build();
        $analysisC = FinalGradeBuilder::anAnalysis()->withMonth(new Month(new \DateTime('2020-2-12')))->build();
        $analysisB = FinalGradeBuilder::anAnalysis()->withMonth(new Month(new \DateTime('2021-3-10')))->build();
        $analysisD = FinalGradeBuilder::anAnalysis()->withMonth(new Month(new \DateTime('2021-3-12')))->build();

        $this->repository->add($analysis);
        $this->repository->add($analysisC);
        $this->repository->add($analysisB);

        $idA = new UserId($analysis->getEmployeeId());
        $idB = new UserId($analysisC->getEmployeeId());
        $idC = new UserId($analysisB->getEmployeeId());

        $foundAnalysis = $this->repository->findByEmployeeIds([$idA, $idB,$idC]);

        $foundEmployeeIds = $foundAnalysis->map(function (FinalGrade $singleAnalysis) {
            return $singleAnalysis->getEmployeeId();
        });

        $this->assertNotEmpty($foundAnalysis);
        $this->assertNotContains($analysisD->getEmployeeId(), $foundEmployeeIds);
        $this->assertContains($analysis->getEmployeeId(), $foundEmployeeIds);
        $this->assertContains($analysisC->getEmployeeId(), $foundEmployeeIds);
        $this->assertContains($analysisB->getEmployeeId(), $foundEmployeeIds);
    }
}
