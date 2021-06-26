<?php


namespace App\Infrastructure\Services;


use App\DataTransferObjects\AssessmentCriteriaDto;
use App\DataTransferObjects\AssessmentDto;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use App\Exceptions\NotFoundEntityException;
use App\Domain\Model\Assessment\AssessmentId;
use App\Domain\Model\Assessment\Check;
use App\Domain\Model\Assessment\Criterion;
use App\Domain\Model\Assessment\Exceptions\NotExistingSelectedOptionException;
use App\Domain\Model\Criterion\CriterionRepository;
use App\Domain\Model\Criterion\Option;
use App\Domain\Model\FinalGrade\FinalGrade;
use App\Domain\Model\FinalGrade\FinalGradeId;
use App\Domain\Model\FinalGrade\FinalGradeRepository;
use App\Exceptions\InvalidRatingMonthException;
use App\Exceptions\MaxReviewsForMonthReachedException;
use App\Exceptions\ModificationRatingException;
use App\Domain\Model\FinalGrade\Month;
use App\Domain\Model\Employee\EmployeeId;
use App\Domain\Model\Employee\EmployeeRepository;
use App\Exceptions\FinalGradeAlreadyExistsException;

class FinalGradeService
{
    private FinalGradeRepository $repository;
    private EntityManagerInterface $em;
    private EmployeeRepository $employeeRepository;
    private CriterionRepository $criterionRepository;

    public function __construct(FinalGradeRepository $repository,
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
     * @throws FinalGradeAlreadyExistsException
     */
    public function create(string $employeeId, \DateTime $month)
    {
        $id = FinalGradeId::next();
        $month = new Month($month);
        $employeeId = new EmployeeId($employeeId);

        $this->employeeRepository->findOrFail((string) $employeeId);

        $result = $this->getMontlyEmployeeAnalyses($employeeId, $month);

        if (!empty($result)) {
            throw new FinalGradeAlreadyExistsException;
        }

        $analyses = new FinalGrade($id, $employeeId, $month);
        $this->repository->add($analyses);
        $this->em->flush();
    }

    private function getMontlyEmployeeAnalyses(EmployeeId $employeeId, Month $month)
    {
        $query = $this->em->getRepository(FinalGrade::class)
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
     * @param string $id
     * @param AssessmentDto $dto
     * @throws NotExistingSelectedOptionException
     * @throws InvalidRatingMonthException
     * @throws MaxReviewsForMonthReachedException
     */
    public function addAssessment(string $id, AssessmentDto $dto) : void
    {
        $analyses = $this->repository->findOrFail($id);
        $checkDto = $dto->getCheckDto();
        $criteriaDto = $dto->getCriteriaDto();
        $check = new Check($checkDto->getServiceDate(), $checkDto->getAmount(), $checkDto->getSaleConversion());
        $criteria = $this->mapAssessmentCriteria($criteriaDto);

        $assessmentId = AssessmentId::next();

        /** @var FinalGrade $analyses */
        $analyses->addAssessment($assessmentId, $check, $criteria);
        $this->em->flush();
    }

    /**
     * @param string $id
     * @param string $assessmentId
     * @param AssessmentDto $dto
     * @throws ModificationRatingException
     */
    public function editAssessment(string $id, string $assessmentId, AssessmentDto $dto)
    {
        /** @var FinalGrade $analyses */
        $analyses = $this->repository->findOrFail($id);

        $checkDto = $dto->getCheckDto();
        $criteriaDto = $dto->getCriteriaDto();
        $check = new Check($checkDto->getServiceDate(), $checkDto->getAmount(), $checkDto->getSaleConversion());
        $criteria = $this->mapAssessmentCriteria($criteriaDto);

        $analyses->editAssessment(new AssessmentId($assessmentId),$check, $criteria);
        $this->em->flush();
    }

    /**
     * @param string $analysisId
     * @param string $assessmentId
     * @throws NotFoundEntityException
     * @throws ModificationRatingException
     */
    public function removeAssessment(string $analysisId, string $assessmentId)
    {
        /** @var FinalGrade $analysis */
        $analysis = $this->repository->findOrFail($analysisId);
        $assessmentId = new AssessmentId($assessmentId);

        $analysis->removeAssessment($assessmentId);
    }

    /**
     * @param AssessmentCriteriaDto $criteriaDto
     * @return array
     */
    private function mapAssessmentCriteria(AssessmentCriteriaDto $criteriaDto): array
    {
        $criteria = $this->criterionRepository->all();
        $selectedCriteria = new ArrayCollection($criteriaDto->getCriteria());

        return $criteria->map(function (\App\Domain\Model\Criterion\Criterion $criterion) use ($selectedCriteria){
           $selectedCriterion = $selectedCriteria->filter(function ($selectedCriterion) use ($criterion){
               return $selectedCriterion['name'] === $criterion->getName();
           })->first();

           if (!$selectedCriterion) {
               throw new EntityNotFoundException(sprintf('Option with %s name doesnt exists',
                   $criterion->getName()));
           }

           $options = $criterion->getOptions()->map(function (Option $option) {
               return new \App\Domain\Model\Assessment\Option($option->getName(), $option->getValue());
           })->toArray();

           return new Criterion(
               $criterion->getName(),
               $options,
               $selectedCriterion['selected'],
               $selectedCriterion['description'] ?? ''
           );
        })->toArray();
    }
}
