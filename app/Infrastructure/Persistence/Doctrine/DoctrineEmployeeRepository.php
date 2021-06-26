<?php


namespace App\Infrastructure\Persistence\Doctrine;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Model\Employee\Employee;
use App\Domain\Model\Employee\EmployeeId;
use App\Domain\Model\Employee\EmployeeRepository;

class DoctrineEmployeeRepository extends AbstractRepository implements EmployeeRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, Employee::class);
    }

    public function getById(EmployeeId $employeeId): ?Employee
    {
        return $this->repository->find($employeeId);
    }

    public function remove(Employee $employee): void
    {
        $this->em->remove($employee);
    }

    public function add(Employee $employee): void
    {
        $this->em->persist($employee);
    }

    public function all(): ArrayCollection
    {
        return new ArrayCollection($this->repository->findAll());
    }
}
