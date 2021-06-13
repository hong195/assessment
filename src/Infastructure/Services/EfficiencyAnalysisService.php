<?php


namespace Infastructure\Services;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Domain\Model\Assessment\AssessmentId;
use Domain\Model\Assessment\Check;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysis;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisId;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisRepository;
use Domain\Model\EfficiencyAnalysis\Month;
use Domain\Model\Employee\EmployeeId;
use Infastructure\Exceptions\EfficiencyAnalysisAlreadyExistsException;

class EfficiencyAnalysisService
{
    private EfficiencyAnalysisRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(EfficiencyAnalysisRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function create(EfficiencyAnalysisId $id, EmployeeId $employeeId, Month $month)
    {
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
     * @param EmployeeId $employeeId
     * @param Check $check
     * @param array $criteria
     * @throws EntityNotFoundException
     * @throws \Domain\Model\EfficiencyAnalysis\Exceptions\InvalidRatingMonthException
     * @throws \Domain\Model\EfficiencyAnalysis\Exceptions\MaxReviewsForMonthReachedException
     */
    public function addAssessment(EfficiencyAnalysisId $id, AssessmentId $assessmentId,EmployeeId $employeeId, Check  $check, array $criteria)
    {
        $analyses = $this->repository->findById($id);

        if (!$analyses) {
            throw new EntityNotFoundException();
        }

        $analyses->addReview($assessmentId, $check, $criteria);
        $this->em->flush();
    }

    public function removeAssessment(EfficiencyAnalysis $analysis, AssessmentId $assessmentId)
    {
        $analysis->removeAssessment($assessmentId);
    }
}
