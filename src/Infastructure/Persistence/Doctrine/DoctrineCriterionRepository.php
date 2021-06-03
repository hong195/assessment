<?php


namespace Infastructure\Persistence\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Domain\Model\Criterion\Criterion;
use Domain\Model\Criterion\CriterionId;
use Domain\Model\Criterion\CriterionRepository;

class DoctrineCriterionRepository implements CriterionRepository
{
    private EntityManagerInterface $em;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Criterion::class);
        $this->em = $em;
    }

    public function all(): ArrayCollection
    {
        return new ArrayCollection($this->repository->findAll());
    }

    public function findById(CriterionId $criterionIdId): ?Criterion
    {
        return $this->repository->find($criterionIdId);
    }

    public function add(Criterion $criterion): void
    {
        $this->em->persist($criterion);
    }

    public function remove(Criterion $criterion): void
    {
        $this->em->remove($criterion);
    }
}
