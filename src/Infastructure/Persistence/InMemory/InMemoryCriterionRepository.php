<?php


namespace Infastructure\Persistence\InMemory;


use Doctrine\Common\Collections\ArrayCollection;
use Domain\Model\Criterion\Criterion;
use Domain\Model\Criterion\CriterionId;
use Domain\Model\Criterion\CriterionRepository;

class InMemoryCriterionRepository implements CriterionRepository
{
    private ArrayCollection $criteria;

    public function __construct()
    {
        $this->criteria = new ArrayCollection([]);
    }

    public function findById(CriterionId $criterionId): ?Criterion
    {
        $collection = $this->criteria->filter(function (Criterion $criterion) use ($criterionId) {
            if ($criterion->getId()->isEqual($criterionId)) {
                return $criterion;
            }
            return null;
        });

        return $collection->isEmpty() ? null : $collection->first();
    }

    public function add(Criterion $criterion) : void
    {
        $this->criteria->add($criterion);
    }

    public function all(): ArrayCollection
    {
        return $this->criteria;
    }

    public function remove(CriterionId $criterionId) : void
    {
        foreach ($this->criteria as $index => $criterion) {
            if ($criterionId->isEqual($criterion->getId())) {
                unset($this->criteria[$index]);
                break;
            }
        }
    }
}
