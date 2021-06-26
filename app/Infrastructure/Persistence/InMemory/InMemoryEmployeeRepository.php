<?php


namespace App\Infrastructure\Persistence\InMemory;


use App\Exceptions\NotFoundEntityException;
use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Model\Employee\Employee;
use App\Domain\Model\Employee\EmployeeId;
use App\Domain\Model\Employee\EmployeeRepository;
use App\Domain\Model\Pharmacy\PharmacyId;

class InMemoryEmployeeRepository implements EmployeeRepository
{
    private ArrayCollection $employees;

    public function __construct()
    {
        $this->employees = new ArrayCollection([]);
    }

    public function getById(EmployeeId $employeeId): ?Employee
    {
        $collection = $this->employees->filter(function ($user) use ($employeeId) {
            if ($employeeId->isEqual($user->getId())) {
                return $user;
            }
            return null;
        });

        return $collection->isEmpty() ? null : $collection->first();
    }

    public function remove(Employee $employee): void
    {
        foreach ($this->employees as $key => $user2) {
            if ($user2->getId()->isEqual($employee->getId())) {
                unset($this->employees[$key]);
                break;
            }
        }
    }

    public function add(Employee $employee): void
    {
        $this->employees->add($employee);
    }

    public function getByPharmacyId(PharmacyId $pharmacyId): ArrayCollection
    {
        return $this->employees->filter(function(Employee $employee) use ($pharmacyId){
            return $employee->getPharmacy()->getId()->isEqual($pharmacyId);
        });
    }

    public function all(): ArrayCollection
    {
        return $this->employees;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->employees->filter(function (Employee $employee) use ($id) {
            return $employee->getId()->isEqual(new EmployeeId($id));
        })->first();
    }
}
