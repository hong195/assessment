<?php


namespace Infastructure\Persistence\Doctrine;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Domain\Id;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysis;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisId;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisRepository;
use Domain\Model\EfficiencyAnalysis\Month;

final class DoctrineEmployeeEfficiencyAnalysesRepository extends  AbstractRepository implements EfficiencyAnalysisRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, EfficiencyAnalysis::class);
    }

    public function add(EfficiencyAnalysis $efficiencyAnalysis): void
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

    public function findByEmployeeId(Id $employeeId): ArrayCollection
    {
        return new ArrayCollection($this->repository->findBy(['employeeId' => $employeeId]));
    }

    public function all(): ArrayCollection
    {
        return new ArrayCollection($this->repository->findAll());
    }

    public function findById(EfficiencyAnalysisId $efficiencyAnalysisId): ?EfficiencyAnalysis
    {
        return $this->repository->find($efficiencyAnalysisId);
    }
}
