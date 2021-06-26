<?php


namespace App\Infrastructure\Services;


use App\DataTransferObjects\EmployeeDto;
use App\Domain\Model\Employee\Employee;
use App\Domain\Model\Employee\EmployeeId;
use App\Domain\Model\Employee\EmployeeRepository;
use App\Domain\Model\Employee\Gender;
use App\Domain\Model\Employee\Name;
use App\Domain\Model\Pharmacy\PharmacyRepository;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeService
{
    private EmployeeRepository $employeeRepository;
    private EntityManagerInterface $em;
    private PharmacyRepository $pharmacyRepository;

    public function __construct(EmployeeRepository $employeeRepository, PharmacyRepository $pharmacyRepository,EntityManagerInterface $em)
    {
        $this->employeeRepository = $employeeRepository;
        $this->em = $em;
        $this->pharmacyRepository = $pharmacyRepository;
    }

    public function create(EmployeeDto $employeeDto)
    {
        $id = EmployeeId::next();
        $pharmacy = $this->pharmacyRepository->findOrFail($employeeDto->getPharmacyId());
        $name = new Name($employeeDto->getFirstName(), $employeeDto->getLastName(), $employeeDto->getMiddleName());
        $gender = new Gender($employeeDto->getGender());
        $employee = new Employee($id,$pharmacy, $name, $employeeDto->getBirthdate(), $gender);

        $this->employeeRepository->add($employee);

        $this->em->flush();
    }
    public function update(string $id, EmployeeDto $employeeDto)
    {
        /** @var Employee $employee */
        $employee = $this->employeeRepository->findOrFail($id);

        $pharmacy = $this->pharmacyRepository->findOrFail($employeeDto->getPharmacyId());
        $name = new Name($employeeDto->getFirstName(), $employeeDto->getLastName(), $employeeDto->getMiddleName());
        $gender = new Gender($employeeDto->getGender());

        $employee->changeName($name);
        $employee->changeBirthDate($employeeDto->getBirthdate());
        $employee->changeGender($gender);
        $employee->changePharmacy($pharmacy);

        $this->em->persist($employee);
        $this->em->flush();
    }

    public function destroy(string $id)
    {
        $employee = $this->employeeRepository->findOrFail($id);

        $this->employeeRepository->remove($employee);
    }
}
