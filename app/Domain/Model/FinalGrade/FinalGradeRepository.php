<?php


namespace App\Domain\Model\FinalGrade;


use App\Domain\Model\Employee\EmployeeId;
use App\Domain\Shared\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;

interface FinalGradeRepository extends AbstractRepository
{
    public function findById(FinalGradeId $efficiencyAnalysisId) : ?FinalGrade;

    public function findByMonth(Month $month) : ArrayCollection;

    public function findByEmployeeIds(array $employeeIds) : ArrayCollection;

    public function findByEmployeeId(EmployeeId $employeeId) : ArrayCollection;

    public function add(FinalGrade $efficiencyAnalysis) : void;

    public function all() : ArrayCollection;

    public function remove(FinalGrade $finalGrade);
}
