<?php


namespace App\Http\DataTransferObjects;


class EfficiencyAnalysesDTO
{
    private string $id;
    private string $employeeId;
    private \DateTime $month;

    public function __construct(string $id, string $employeeId, \DateTime $month)
    {
        $this->id = $id;
        $this->employeeId = $employeeId;
        $this->month = $month;
    }

    public function getMonth(): \DateTime
    {
        return $this->month;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmployeeId(): string
    {
        return $this->employeeId;
    }
}
