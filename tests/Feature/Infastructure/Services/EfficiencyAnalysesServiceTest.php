<?php


namespace Tests\Feature\Infastructure\Services;


use Doctrine\ORM\EntityManagerInterface;
use Domain\Id;
use Domain\Model\Assessment\AssessmentId;
use Domain\Model\Assessment\Criterion;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysis;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisId;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisRepository;
use Domain\Model\EfficiencyAnalysis\Month;
use Domain\Model\Employee\EmployeeId;
use Infastructure\Exceptions\EfficiencyAnalysisAlreadyExistsException;
use Infastructure\Services\EfficiencyAnalysisService;
use Tests\Feature\DoctrineMigrationsTrait;
use Tests\TestCase;
use Tests\Unit\Domain\Model\Builders\CheckBuilder;
use Tests\Unit\Domain\Model\Builders\EfficiencyAnalysisBuilder;
use Tests\Unit\Domain\Model\Builders\EmployeeBuilder;

/**
 * @group integration
 */
class EfficiencyAnalysesServiceTest extends TestCase
{
    use DoctrineMigrationsTrait;

    private EfficiencyAnalysisRepository $repository;

    private EntityManagerInterface $em;

    private EfficiencyAnalysisService $analysisService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resetMigrations();

        $this->repository = app()->make(EfficiencyAnalysisRepository::class);
        $this->em = app()->make(EntityManagerInterface::class);
        $this->analysisService = new EfficiencyAnalysisService($this->repository, $this->em);
    }

    private function getMontlyEmployeeAnalyses(EmployeeId $employeeId, \DateTime $month)
    {
        $query = $this->em->getRepository(EfficiencyAnalysis::class)
            ->createQueryBuilder('e')
            ->where('YEAR(e.month.date) = :year')
            ->andWhere('MONTH(e.month.date) = :month')
            ->andWhere('e.employeeId = :employee_id')
            ->setParameter('year', $month->format('Y'))
            ->setParameter('month', $month->format('m'))
            ->setParameter('employee_id', (string) $employeeId)
            ->getQuery();

        return $query->getResult();
    }

    public function test_can_create_an_efficiency_analyses()
    {
        $anEmployeeId = EmployeeBuilder::anEmployee()->build()->getId();
        $month = new \DateTime('2020-12-01');

        $this->analysisService->create($anEmployeeId, $month);
        $foundAnalyses = $this->getMontlyEmployeeAnalyses($anEmployeeId, $month);
        /** @var EfficiencyAnalysis $addedAnalyses */
        $addedAnalyses = reset($foundAnalyses);

        $this->assertEquals((string) $addedAnalyses->getMonth(), $month->format('Y-m-d'));
        $this->assertTrue($addedAnalyses->getEmployeeId()->isEqual($anEmployeeId));
    }

    public function test_expects_exception_when_employee_has_already_analyses_for_a_certain_month()
    {
        $anEmployeeId = EmployeeBuilder::anEmployee()->build()->getId();
        $date = new \DateTime('2020-4');
        $aprilMonth = new Month($date);
        $aprilEfficiencyAnalyses = EfficiencyAnalysisBuilder::anAnalysis()
                ->withEmployee($anEmployeeId)
                ->withMonth($aprilMonth)
                ->build();

        $this->repository->add($aprilEfficiencyAnalyses);
        $this->em->flush();

        $this->expectException(EfficiencyAnalysisAlreadyExistsException::class);

        $this->analysisService->create($anEmployeeId, $date);
    }

    public function test_can_add_assessment()
    {
        $id = new EfficiencyAnalysisId(EfficiencyAnalysisId::next());
        $anEmployeeId = EmployeeBuilder::anEmployee()->build()->getId();

        $analyses = EfficiencyAnalysisBuilder::anAnalysis()->withId($id)->build();
        $this->repository->add($analyses);
        $this->em->flush();
        $assessmentId = AssessmentId::next();
        $check = CheckBuilder::aCheck()->build();
        $criteria = [new Criterion('Ethics', [new \Domain\Model\Assessment\Option('yes', 1)], 'yes')];

        $this->analysisService->addAssessment($id, $assessmentId, $anEmployeeId, $check, $criteria);

        $this->assertDatabaseCount('assessments', 1);
    }

    public function test_can_remove_assessment()
    {
        $id = new EfficiencyAnalysisId(EfficiencyAnalysisId::next());
        $anEmployeeId = EmployeeBuilder::anEmployee()->build()->getId();

        $analyses = EfficiencyAnalysisBuilder::anAnalysis()->withId($id)->build();
        $this->repository->add($analyses);
        $this->em->flush();
        $assessmentId = AssessmentId::next();
        $check = CheckBuilder::aCheck()->build();
        $criteria = [new Criterion('Ethics', [new \Domain\Model\Assessment\Option('yes', 1)], 'yes')];

        $this->analysisService->addAssessment($id, $assessmentId, $anEmployeeId, $check, $criteria);
        $this->analysisService->removeAssessment($analyses, $assessmentId);

        $updatedAnalyses = $this->repository->findById($id);
        $missingAssessment = $updatedAnalyses->getAssessments()->filter(function ($assessment) use ($assessmentId){
            return $assessment->getId()->isEqual($assessmentId);
        })
            ->isEmpty();

        $this->assertTrue($missingAssessment);
    }
}
