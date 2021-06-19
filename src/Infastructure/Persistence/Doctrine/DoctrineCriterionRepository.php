<?php


namespace Infastructure\Persistence\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Domain\Model\Criterion\Criterion;
use Domain\Model\Criterion\CriterionId;
use Domain\Model\Criterion\CriterionRepository;

class DoctrineCriterionRepository extends AbstractRepository implements CriterionRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, Criterion::class);
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

    public function findByName(string $name)
    {
        return $this->repository->findOneBy([
            'name' => $name
        ]);
    }

    public function remove(Criterion $criterion): void
    {
        $this->em->remove($criterion);
    }
}
