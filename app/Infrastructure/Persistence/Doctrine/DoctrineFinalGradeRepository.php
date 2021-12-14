<?php


namespace App\Infrastructure\Persistence\Doctrine;


use App\Domain\Model\Employee\EmployeeId;
use App\Domain\Model\FinalGrade\FinalGrade;
use App\Domain\Model\FinalGrade\FinalGradeId;
use App\Domain\Model\FinalGrade\FinalGradeRepository;
use App\Domain\Model\FinalGrade\Month;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineFinalGradeRepository extends  AbstractRepository implements FinalGradeRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, FinalGrade::class);
    }

    public function add(FinalGrade $efficiencyAnalysis): void
    {
        $this->em->persist($efficiencyAnalysis);
    }

    public function findByMonth(Month $month): ArrayCollection
    {
        $query = $this->repository->createQueryBuilder('e')->select('e')
            ->where('YEAR(e.month.date) = :year')
            ->andWhere('MONTH(e.month.date) = :month')
            ->setParameter('year', $month->getYear())
            ->setParameter('month', $month->getMonth())
            ->getQuery();

        return new ArrayCollection($query->getResult());
    }

    public function findByEmployeeIds(array $employeeIds): ArrayCollection
    {
        $qb = $this->repository->createQueryBuilder('e');
        $query = $qb->select('e')
            ->add('where', $qb->expr()->in('e.employeeId', $employeeIds));

        return new ArrayCollection($query->getQuery()->getResult());
    }

    public function findByEmployeeId(EmployeeId $employeeId): ArrayCollection
    {
        return new ArrayCollection($this->repository->findBy(['employeeId' => $employeeId]));
    }

    public function all(): ArrayCollection
    {
        return new ArrayCollection($this->repository->findAll());
    }

    public function findById(FinalGradeId $efficiencyAnalysisId): ?FinalGrade
    {
        return $this->repository->find($efficiencyAnalysisId);
    }

    public function remove(FinalGrade $finalGrade)
    {
        $this->em->remove($finalGrade);
    }
}
