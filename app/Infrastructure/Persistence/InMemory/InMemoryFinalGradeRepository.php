<?php


namespace App\Infrastructure\Persistence\InMemory;


use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Shared\Id;
use App\Domain\Model\FinalGrade\FinalGrade;
use App\Domain\Model\FinalGrade\FinalGradeId;
use App\Domain\Model\FinalGrade\FinalGradeRepository;
use App\Domain\Model\FinalGrade\EmployeeId;
use App\Domain\Model\FinalGrade\Month;

class InMemoryFinalGradeRepository implements FinalGradeRepository
{
    private ArrayCollection $analysis;

    public function __construct()
    {
        $this->analysis = new ArrayCollection([]);
    }

    public function findById(FinalGradeId $efficiencyAnalysisId): ?FinalGrade
    {
        $collection = $this->analysis->filter(function ($singleAnalysis) use ($efficiencyAnalysisId) {
            if ($efficiencyAnalysisId->isEqual($singleAnalysis->getId())) {
                return $singleAnalysis;
            }
            return null;
        });

        return $collection->isEmpty() ? null : $collection->first();
    }

    public function findByMonth(Month $month) : ArrayCollection
    {
        return $this->analysis->filter(function (FinalGrade  $singleAnalysis) use ($month){
            return $singleAnalysis->getMonth()->isEqual($month);
        });
    }

    public function findByEmployeeIds(array $employeeIds) : ArrayCollection
    {
        return $this->analysis->filter(function (FinalGrade $singleAnalysis)  use ($employeeIds){
            foreach ($employeeIds as $id) {
                if ((string) $singleAnalysis->getEmployeeId() === (string) $id) {
                    return $singleAnalysis;
                }
            }
            return null;
        });
    }

    public function findByEmployeeId(Id $employeeId): ArrayCollection
    {
        return $this->analysis->filter(function (FinalGrade  $singleAnalysis) use ($employeeId){
           return  (string) $singleAnalysis->getEmployeeId() === (string) $employeeId;
        });
    }

    public function add(FinalGrade $efficiencyAnalysis) : void
    {
        $this->analysis->add($efficiencyAnalysis);
    }

    public function all(): ArrayCollection
    {
        return $this->analysis;
    }
}
