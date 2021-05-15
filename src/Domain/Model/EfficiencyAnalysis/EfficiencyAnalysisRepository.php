<?php


namespace Domain\Model\EfficiencyAnalysis;


interface EfficiencyAnalysisRepository
{
    public function findByEfficiencyAnalysisId(EfficiencyAnalysisId $efficiencyAnalysisId) : EfficiencyAnalysis;

    public function findByMonth(Month $month);

    public function findByEmployeeIds(EmployeeId $employeeId);

    public function findByEmployeeId(EmployeeId $employeeId) : EfficiencyAnalysis;

    public function add(EfficiencyAnalysisId $efficiencyAnalysisId) : EfficiencyAnalysis;
}
