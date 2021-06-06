<?php


namespace Infastructure\Persistence\Doctrine;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Model\Employee\Employee;
use Domain\Model\Employee\EmployeeId;
use Domain\Model\Employee\EmployeeRepository;

class DoctrineEmployeeRepository implements EmployeeRepository
{
    private EntityManagerInterface $em;
    private \Doctrine\Persistence\ObjectRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Employee::class);
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
