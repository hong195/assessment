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

final class DoctrineEmployeeEfficiencyAnalysesRepository implements EfficiencyAnalysisRepository
{
    private EntityManagerInterface $em;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(EfficiencyAnalysis::class);
        $this->em = $em;
    }

    public function add(EfficiencyAnalysis $efficiencyAnalysis) : void
    {
        $this->em->persist($efficiencyAnalysis);
    }

    public function findByMonth(Month $month): ArrayCollection
    {
        return $this->repository->findBy(['month_date' => $month]);
    }

    public function findByEmployeeIds(array $employeeIds): ArrayCollection
    {
        return $this->repository->findBy(['id' => $employeeIds]);
    }

    public function findByEmployeeId(Id $employeeId): ArrayCollection
    {
        return $this->repository->findBy(['employee_id' => $employeeId]);
    }

    public function all(): ArrayCollection
    {
        return $this->repository->findAll();
    }

    public function findById(EfficiencyAnalysisId $efficiencyAnalysisId): ?EfficiencyAnalysis
    {
        return $this->repository->find($efficiencyAnalysisId);
    }
}
