<?php


namespace Infastructure\Persistence\InMemory;


use Doctrine\Common\Collections\ArrayCollection;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysis;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisId;
use Domain\Model\EfficiencyAnalysis\EfficiencyAnalysisRepository;
use Domain\Model\EfficiencyAnalysis\EmployeeId;
use Domain\Model\EfficiencyAnalysis\Month;

class InMemoryEfficiencyAnalysisRepository implements EfficiencyAnalysisRepository
{
    private ArrayCollection $analysis;

    public function __construct()
    {
        $this->analysis = new ArrayCollection([]);
    }

    public function findById(EfficiencyAnalysisId $efficiencyAnalysisId): ?EfficiencyAnalysis
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
        return $this->analysis->filter(function (EfficiencyAnalysis  $singleAnalysis) use ($month){
            return $singleAnalysis->getMonth()->isEqual($month);
        });
    }

    public function findByEmployeeIds(array $employeeIds) : ArrayCollection
    {
        return $this->analysis->filter(function (EfficiencyAnalysis $singleAnalysis)  use ($employeeIds){
            foreach ($employeeIds as $id) {
                if ($singleAnalysis->getEmployeeId()->isEqual($id)) {
                    return $singleAnalysis;
                }
            }
            return null;
        });
    }

    public function findByEmployeeId(EmployeeId $employeeId): ArrayCollection
    {
        return $this->analysis->filter(function (EfficiencyAnalysis  $singleAnalysis) use ($employeeId){
           return  $employeeId->isEqual($singleAnalysis->getEmployeeId());
        });
    }

    public function add(EfficiencyAnalysis $efficiencyAnalysis) : void
    {
        $this->analysis->add($efficiencyAnalysis);
    }

    public function all(): ArrayCollection
    {
        return $this->analysis;
    }
}
