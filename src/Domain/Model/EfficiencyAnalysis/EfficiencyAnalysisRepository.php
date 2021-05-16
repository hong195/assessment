<?php


namespace Domain\Model\EfficiencyAnalysis;


use Doctrine\Common\Collections\ArrayCollection;
use Domain\Id;

interface EfficiencyAnalysisRepository
{
    public function findById(EfficiencyAnalysisId $efficiencyAnalysisId) : ?EfficiencyAnalysis;

    public function findByMonth(Month $month) : ArrayCollection;

    public function findByEmployeeIds(array $employeeIds) : ArrayCollection;

    public function findByEmployeeId(Id $employeeId) : ArrayCollection;

    public function add(EfficiencyAnalysis $efficiencyAnalysis) : void;

    public function all() : ArrayCollection;
}
