<?php


namespace App\Infrastructure\Services;


use App\DataTransferObjects\AssessmentCriteriaDto;
use App\DataTransferObjects\AssessmentDto;
use App\Domain\Model\Assessment\Assessment;
use App\Domain\Model\Assessment\Reviewer;
use App\Domain\Model\Assessment\ReviewerId;
use App\Domain\Model\Assessment\ReviewerName;
use App\Domain\Shared\Id;
use App\Jobs\SendToPharmacyEmailJob;
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
use Illuminate\Support\Facades\Auth;

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
        $assessment = $analyses->addAssessment($assessmentId, $check, $criteria);
        $assessment->assignReviewer($this->getReviewer());

        $this->em->flush();

        if ($analyses->isMaxReviewsAdded()) {
            SendToPharmacyEmailJob::dispatch((string) $analyses->getEmployeeId());
        }
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
               $selectedCriterion['description'] ?? '',
               $selectedCriterion['label'] ?? '',
           );
        })->toArray();
    }

    /**
     * @param string $finalGradeId
     * @param string $assessmentId
     * @return mixed
     * @throws NotFoundEntityException
     */
    public function getAssessment(string $finalGradeId, string $assessmentId)
    {
        $finalGrade = $this->repository->findOrFail($finalGradeId);

        return $finalGrade->getAssessments()->filter(function(Assessment $assessment) use ($assessmentId){
            $assessmentId = new AssessmentId($assessmentId);

            return $assessment->getId()->isEqual($assessmentId);
        })
            ->first();
    }

    private function getReviewer(): Reviewer
    {
        $firstName = (string) Auth::user()->getFullName()->firstName();
        $lastName = (string) Auth::user()->getFullName()->lastName();
        $middleName = (string) Auth::user()->getFullName()->patronymic();

        $reviewerId = new ReviewerId((string) Auth::user()->getId());
        $reviewerName = new ReviewerName($firstName, $lastName, $middleName);

        return new Reviewer($reviewerId, $reviewerName);
    }

    /**
     * @throws NotFoundEntityException
     * @throws \App\Exceptions\UncompletedFinalGradeException
     */
    public function addDescription(string $finalGradeId, string $description)
    {
        /** @var FinalGrade $finalGrade */
        $finalGrade = $this->repository->findOrFail($finalGradeId);

        $finalGrade->addDescription($description);
        $this->em->flush();
    }

    public function remove(string $efficiencyAnalysisId)
    {
        /** @var FinalGrade $analysis */
        $analysis = $this->repository->find($efficiencyAnalysisId);

        if ($analysis->isCompleted())  {
            throw new \Exception('Невозможно удалить завершенную итоговую оценку');
        }

        $this->repository->remove($analysis);
        $this->em->flush();
    }
}
