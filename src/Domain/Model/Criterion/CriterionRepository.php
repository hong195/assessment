<?php


namespace Domain\Model\Criterion;


interface CriterionRepository
{
    public function getAll();

    public function getById(CriterionId $criterionId);

    public function save(Criterion $criterion) : void;

    public function delete(CriterionId $criterionId) : void;
}
