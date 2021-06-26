<?php


namespace App\Domain\Model\Employee;


use App\Domain\Shared\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;

interface EmployeeRepository extends AbstractRepository
{
    public function getById(EmployeeId $employeeId) : ?Employee;

    public function remove(Employee $employee) : void;

    public function add(Employee $employee) : void;

    public function all() : ArrayCollection;
}
