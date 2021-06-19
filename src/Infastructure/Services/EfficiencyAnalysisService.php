<?php


namespace Infastructure\Services;


use App\Http\DataTransferObjects\AssessmentCriteriaDto;
use App\Http\DataTransferObjects\AssessmentDto;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Exceptions\NotFoundEntityException;
use Domain\Model\Assessment\AssessmentId;
use Domain\Model\Assessment\Check;
use Domain\Model\Assessment\Criterion;
use Domain\Model\Assessment\Exceptions\NotExistingSelectedOptionException;
use Domain\Model\Criterion\CriterionRepository;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysis;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisId;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisRepository;
use Domain\Model\EfficiencyAnalysis\Exceptions\InvalidRatingMonthException;
use Domain\Model\EfficiencyAnalysis\Exceptions\MaxReviewsForMonthReachedException;
use Domain\Model\EfficiencyAnalysis\Exceptions\ModificationRatingException;
use Domain\Model\EfficiencyAnalysis\Month;
use Domain\Model\Employee\EmployeeId;
use Domain\Model\Employee\EmployeeRepository;
use Infastructure\Exceptions\EfficiencyAnalysisAlreadyExistsException;

class EfficiencyAnalysisService
{
    private EfficiencyAnalysisRepository $repository;
    private EntityManagerInterface $em;
    private EmployeeRepository $employeeRepository;
    private CriterionRepository $criterionRepository;

    public function __construct(EfficiencyAnalysisRepository $repository,
                                EmployeeRepository $employeeRepository,
                                CriterionRepository $criterionRepository,
                                EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->employeeRepository = $employeeRepository;
        $this->criterionRepository = $criterionRepository;
    }

    /**
     * @param string $employeeId
     * @param \DateTime $month
     * @throws EfficiencyAnalysisAlreadyExistsException
     */
    public function create(string $employeeId, \DateTime $month)
    {
        $id = EfficiencyAnalysisId::next();
        $month = new Month($month);
        $employeeId = new EmployeeId($employeeId);

        $this->employeeRepository->findOrFail((string) $employeeId);

        $result = $this->getMontlyEmployeeAnalyses($employeeId, $month);

        if (!empty($result)) {
            throw new EfficiencyAnalysisAlreadyExistsException;
        }

        $analyses = new EfficiencyAnalysis($id, $employeeId, $month);
        $this->repository->add($analyses);
        $this->em->flush();
    }

    private function getMontlyEmployeeAnalyses(EmployeeId $employeeId, Month $month)
    {
        $query = $this->em->getRepository(EfficiencyAnalysis::class)
            ->createQueryBuilder('e')
            ->where('YEAR(e.month.date) = :year')
            ->andWhere('MONTH(e.month.date) = :month')
            ->andWhere('e.employeeId = :employee_id')
            ->setParameter('year', $month->getYear())
            ->setParameter('month', $month->getMonth())
            ->setParameter('employee_id', (string) $employeeId)
            ->getQuery();

        return $query->getArrayResult();
    }

    /**
     * @param EfficiencyAnalysisId $id
     * @param AssessmentDto $dto
     * @throws NotExistingSelectedOptionException
     * @throws InvalidRatingMonthException
     * @throws MaxReviewsForMonthReachedException
     */
    public function addAssessment(EfficiencyAnalysisId $id, AssessmentDto $dto) : void
    {
        $analyses = $this->repository->findOrFail($id);
        $checkDto = $dto->getCheckDto();
        $criteriaDto = $dto->getCriteriaDto();
        $check = new Check($checkDto->getServiceDate(), $checkDto->getAmount(), $checkDto->getSaleConversion());
        $criteria = $this->mapAssessmentCriteria($criteriaDto);
        $assessmentId = AssessmentId::next();

        /** @var EfficiencyAnalysis $analyses */
        $analyses->addAssessment($assessmentId, $check, $criteria);
        $this->em->flush();
    }

    /**
     * @param AssessmentCriteriaDto $criteriaDto
     * @return array
     */
    private function mapAssessmentCriteria(AssessmentCriteriaDto $criteriaDto): array
    {
        $criteria = $this->criterionRepository->all();

        foreach ($criteria as $criterion) {

        }
        return array_map(function ($criterion) {
            return new Criterion($criterion['name'], [], $criterion['selected'], $criterion['description']);
        }, $criteriaDto->getCriteria());
    }
    /**
     * @param string $analysisId
     * @param string $assessmentId
     * @throws NotFoundEntityException
     * @throws ModificationRatingException
     */
    public function removeAssessment(string $analysisId, string $assessmentId)
    {

        /** @var EfficiencyAnalysis $analysis */
        $analysis = $this->repository->findOrFail($analysisId);
        $assessmentId = new AssessmentId($assessmentId);

        $analysis->removeAssessment($assessmentId);
    }
}
