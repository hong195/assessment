<?php


namespace Domain\Model\Criterion;


interface CriterionRepository
{
    public function all();

    public function getById(CriterionId $criterionId);

    public function add(Criterion $criterion) : void;

    public function delete(CriterionId $criterionId) : void;
}
