<?php


namespace Domain\Model\Employee;


use Doctrine\Common\Collections\ArrayCollection;
use Domain\Model\Pharmacy\PharmacyId;

interface EmployeeRepository
{
    public function getById(EmployeeId $employeeId) : ?Employee;

    public function remove(Employee $employee) : void;

    public function add(Employee $employee) : void;

    public function all() : ArrayCollection;
}
