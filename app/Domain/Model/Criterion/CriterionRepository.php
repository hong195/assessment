<?php


namespace App\Domain\Model\Criterion;


use App\Domain\Shared\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @method findOrFail(string $id)
 */
interface CriterionRepository extends AbstractRepository
{
    public function all() : ArrayCollection;

    public function findById(CriterionId $criterionIdId): ?Criterion;

    public function add(Criterion $criterion): void;

    public function remove(Criterion $criterion): void;

    public function findByName(string $name);
}
