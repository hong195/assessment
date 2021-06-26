<?php


namespace Tests\Feature\Infastructure\Persistence\InMemory;


use App\Infrastructure\Persistence\InMemory\InMemoryEmployeeRepository;
use Tests\TestCase;
use Tests\Builders\EmployeeBuilder;
use Tests\Builders\PharmacyBuilder;

class EmployeeRepositoryTest extends TestCase
{
    private InMemoryEmployeeRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new InMemoryEmployeeRepository();
    }

    public function test_can_delete_employee()
    {
        $anEmployee = EmployeeBuilder::anEmployee()->build();
        $anEmployee2 = EmployeeBuilder::anEmployee()->build();

        $this->repository->add($anEmployee);
        $this->repository->add($anEmployee2);
        $this->repository->remove($anEmployee);

        $this->assertNotContains($anEmployee, $this->repository->all());
        $this->assertContains($anEmployee2, $this->repository->all());
    }

    public function test_can_get_all()
    {
        $anEmployee = EmployeeBuilder::anEmployee()->build();
        $anEmployee2 = EmployeeBuilder::anEmployee()->build();

        $this->repository->add($anEmployee);
        $this->repository->add($anEmployee2);

        $this->assertContains($anEmployee, $this->repository->all());
        $this->assertContains($anEmployee2, $this->repository->all());
    }

    public function test_can_get_by_pharmacy_id()
    {
        $pharmacyA = PharmacyBuilder::aPharmacy()->build();
        $anEmployeeA = EmployeeBuilder::anEmployee()->withPharmacy($pharmacyA)->build();
        $pharmacyB = PharmacyBuilder::aPharmacy()->build();
        $anEmployeeB = EmployeeBuilder::anEmployee()->withPharmacy($pharmacyB)->build();

        $this->repository->add($anEmployeeA);
        $this->repository->add($anEmployeeB);
        $foundEmployees = $this->repository->getByPharmacyId($pharmacyA->getId());

        $this->assertContains($anEmployeeA, $foundEmployees);
    }
}
