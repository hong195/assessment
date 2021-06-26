<?php


namespace App\DataTransferObjects;


class FinalGradeDto
{
    private string $employeeId;
    private \DateTime $month;

    public function __construct(string $employeeId, \DateTime $month)
    {
        $this->employeeId = $employeeId;
        $this->month = $month;
    }

    public function getMonth(): \DateTime
    {
        return $this->month;
    }

    public function getEmployeeId(): string
    {
        return $this->employeeId;
    }
}
