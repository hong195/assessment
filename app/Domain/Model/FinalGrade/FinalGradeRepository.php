<?php


namespace App\Domain\Model\FinalGrade;


use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Shared\Id;

interface FinalGradeRepository
{
    public function findById(FinalGradeId $efficiencyAnalysisId) : ?FinalGrade;

    public function findByMonth(Month $month) : ArrayCollection;

    public function findByEmployeeIds(array $employeeIds) : ArrayCollection;

    public function findByEmployeeId(Id $employeeId) : ArrayCollection;

    public function add(FinalGrade $efficiencyAnalysis) : void;

    public function all() : ArrayCollection;
}
