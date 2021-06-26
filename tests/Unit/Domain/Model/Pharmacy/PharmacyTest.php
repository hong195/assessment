<?php


namespace Tests\Unit\Domain\Model\Pharmacy;


use App\Domain\Model\Pharmacy\Email;
use App\Domain\Model\Pharmacy\PharmacyNumber;
use PHPUnit\Framework\TestCase;
use Tests\Builders\EmployeeBuilder;
use Tests\Builders\PharmacyBuilder;

class PharmacyTest extends TestCase
{
    public function test_can_change_email()
    {
        $pharmacy = PharmacyBuilder::aPharmacy()->withEmail(new Email('old-email@gmail.com'))->build();
        $newEmail = new Email('new-email@gmail.com');

        $pharmacy->changeEmail($newEmail);

        $this->assertEquals($newEmail, $pharmacy->getEmail());
        $this->assertEquals('new-email@gmail.com', (string) $pharmacy->getEmail());
    }

    public function test_can_change_number()
    {
        $pharmacy = PharmacyBuilder::aPharmacy()->withNumber(new PharmacyNumber('old-number'))->build();
        $newNumber = new PharmacyNumber('new-number');

        $pharmacy->changeNumber($newNumber);

        $this->assertEquals($newNumber, $pharmacy->getNumber());
        $this->assertEquals('new-number', (string) $pharmacy->getNumber());
    }

    public function test_can_add_employees()
    {
        $pharmacy = PharmacyBuilder::aPharmacy()->build();
        $employee = EmployeeBuilder::anEmployee()->build();
        $pharmacy->addEmployee($employee);

        $this->assertNotEmpty($pharmacy->getEmployees());
        $this->assertContains($employee, $pharmacy->getEmployees());
    }

    public function test_can_remove_employee()
    {
        $pharmacy = PharmacyBuilder::aPharmacy()->build();
        $employeeA = EmployeeBuilder::anEmployee()->build();
        $employeeB = EmployeeBuilder::anEmployee()->build();

        $pharmacy->addEmployee($employeeA);
        $pharmacy->addEmployee($employeeB);

        $pharmacy->resign($employeeA);

        $this->assertNotContains($employeeA, $pharmacy->getEmployees());
        $this->assertContains($employeeB, $pharmacy->getEmployees());
    }
}
