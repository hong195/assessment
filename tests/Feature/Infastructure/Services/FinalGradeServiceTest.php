<?php


namespace Tests\Feature\Infastructure\Services;


use App\DataTransferObjects\AssessmentCriteriaDto;
use App\DataTransferObjects\AssessmentDto;
use App\DataTransferObjects\CheckDto;
use App\DataTransferObjects\CriterionOptionDto;
use App\Domain\Model\Criterion\CriterionRepository;
use App\Domain\Model\Criterion\OptionId;
use Doctrine\ORM\EntityManagerInterface;
use App\Exceptions\NotFoundEntityException;
use App\Domain\Model\Assessment\AssessmentId;
use App\Domain\Model\Assessment\Criterion;
use App\Domain\Model\FinalGrade\FinalGrade;
use App\Domain\Model\FinalGrade\FinalGradeId;
use App\Domain\Model\FinalGrade\FinalGradeRepository;
use App\Domain\Model\FinalGrade\Month;
use App\Domain\Model\Employee\EmployeeId;
use App\Domain\Model\Employee\EmployeeRepository;
use App\Exceptions\FinalGradeAlreadyExistsException;
use App\Infrastructure\Services\FinalGradeService;
use Tests\Builders\CriterionBuilder;
use Tests\Feature\DoctrineMigrationsTrait;
use Tests\TestCase;
use Tests\Builders\CheckBuilder;
use Tests\Builders\FinalGradeBuilder;
use Tests\Builders\EmployeeBuilder;
use Tests\Builders\PharmacyBuilder;

/**
 * @group integration
 */
class FinalGradeServiceTest extends TestCase
{
    use DoctrineMigrationsTrait;

    private FinalGradeRepository $repository;

    private EntityManagerInterface $em;

    private FinalGradeService $analysisService;

    private EmployeeRepository $employeeRepository;

    private CriterionRepository $criterionRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resetMigrations();

        $this->repository = app()->make(FinalGradeRepository::class);
        $this->criterionRepository = app()->make(CriterionRepository::class);
        $this->employeeRepository = app()->make(EmployeeRepository::class);
        $this->em = app()->make(EntityManagerInterface::class);
        $this->analysisService = new FinalGradeService($this->repository,
            $this->employeeRepository,
            $this->criterionRepository,
            $this->em);
    }

    private function getMontlyEmployeeAnalyses(EmployeeId $employeeId, \DateTime $month)
    {
        $query = $this->em->getRepository(FinalGrade::class)
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
        $pharmacy = PharmacyBuilder::aPharmacy()->build();
        $employee = EmployeeBuilder::anEmployee()->withId($anEmployeeId)->withPharmacy($pharmacy)->build();

        $this->em->persist($pharmacy);
        $this->em->persist($employee);
        $this->analysisService->create($anEmployeeId, $month);
        $this->em->flush();
        $foundAnalyses = $this->getMontlyEmployeeAnalyses($anEmployeeId, $month);
        /** @var FinalGrade $addedAnalyses */
        $addedAnalyses = reset($foundAnalyses);

        $this->assertEquals((string) $addedAnalyses->getMonth(), $month->format('Y-m-d'));
        $this->assertTrue($addedAnalyses->getEmployeeId()->isEqual($anEmployeeId));
    }

    public function test_expects_exception_when_employee_doesnt_exists()
    {
        $anEmployeeId = EmployeeBuilder::anEmployee()->build()->getId();
        $month = new \DateTime('2020-12-01');

        $this->expectException(NotFoundEntityException::class);

        $this->analysisService->create($anEmployeeId, $month);
    }

    public function test_expects_exception_when_employee_has_already_analyses_for_a_certain_month()
    {
        $date = new \DateTime('2020-4');
        $anEmployeeId = EmployeeBuilder::anEmployee()->build()->getId();
        $pharmacy = PharmacyBuilder::aPharmacy()->build();
        $employee = EmployeeBuilder::anEmployee()->withId($anEmployeeId)->withPharmacy($pharmacy)->build();

        $this->em->persist($pharmacy);
        $this->em->persist($employee);
        $aprilMonth = new Month($date);

        $aprilEfficiencyAnalyses = FinalGradeBuilder::anAnalysis()
                ->withEmployee($anEmployeeId)
                ->withMonth($aprilMonth)
                ->build();

        $this->repository->add($aprilEfficiencyAnalyses);
        $this->em->flush();

        $this->expectException(FinalGradeAlreadyExistsException::class);

        $this->analysisService->create($anEmployeeId, $date);
    }

    public function test_can_add_assessment()
    {
        $id = new FinalGradeId(FinalGradeId::next());

        $analyses = FinalGradeBuilder::anAnalysis()->withId($id)->build();
        $this->repository->add($analyses);

        $criterion = CriterionBuilder::aCriterion()->withName('Ethics')->build();
        $criterion->addOption(OptionId::next(), 'yes', 1);
        $this->criterionRepository->add($criterion);

        $this->em->flush();

        $check = CheckBuilder::aCheck()->build();
        $checkDto = new CheckDto($check->getServiceDate(), $check->getAmount(), $check->getSaleConversion());
        $criteriaDto = AssessmentCriteriaDto::fromArray([
            [
                'name' => 'Ethics',
                'selected' => 'yes',
                'description' => 'Test description'
            ],
        ]);
        $dto = new AssessmentDto($checkDto, $criteriaDto);

        $this->analysisService->addAssessment((string) $id, $dto);

        $this->assertDatabaseCount('assessments', 1);
    }

    public function test_can_remove_assessment()
    {
        $id = new FinalGradeId(FinalGradeId::next());

        $analyses = FinalGradeBuilder::anAnalysis()->withId($id)->build();
        $this->repository->add($analyses);
        $this->em->flush();
        $assessmentId =  AssessmentId::next();

        $check = CheckBuilder::aCheck()->build();
        $checkDto = new CheckDto($check->getServiceDate(), $check->getAmount(), $check->getSaleConversion());
        $criteriaDto = AssessmentCriteriaDto::fromArray([
            [
                'name' => 'Ethics',
                'selected' => 'yes',
                'description' => 'Test description'
            ],
        ]);
        $dto = new AssessmentDto($checkDto, $criteriaDto);

        $this->analysisService->addAssessment($id, $dto);
        $this->analysisService->removeAssessment((string) $analyses->getId(), (string) $assessmentId);

        $updatedAnalyses = $this->repository->findById($id);
        $missingAssessment = $updatedAnalyses->getAssessments()->filter(function ($assessment) use ($assessmentId){
            return $assessment->getId()->isEqual($assessmentId);
        })
            ->isEmpty();

        $this->assertTrue($missingAssessment);
    }
}
