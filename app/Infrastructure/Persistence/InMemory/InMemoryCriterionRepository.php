<?php


namespace App\Infrastructure\Persistence\InMemory;


use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Model\Criterion\Criterion;
use App\Domain\Model\Criterion\CriterionId;
use App\Domain\Model\Criterion\CriterionRepository;

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

    public function remove(Criterion $criterion) : void
    {
        foreach ($this->criteria as $index => $singleCriterion) {
            if ($criterion->getId()->isEqual($singleCriterion->getId())) {
                unset($this->criteria[$index]);
                break;
            }
        }
    }

    public function findByName(string $name)
    {
        return $this->criteria->filter(function ($criterion) use ($name){
            return $criterion->getName() === $name;
        })->first();
    }

    public function findOrFail($id) : mixed
    {
    }
}
