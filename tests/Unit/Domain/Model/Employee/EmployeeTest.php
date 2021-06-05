<?php

namespace Tests\Unit\Domain\Model\Employee;

use Domain\Model\Employee\Gender;
use Domain\Model\Employee\Name;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Domain\Model\Builders\EmployeeBuilder;

class EmployeeTest extends TestCase
{
    private EmployeeBuilder $employeeBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employeeBuilder = EmployeeBuilder::anEmployee();
    }

    public function test_can_change_name()
    {
        $oldName = new Name('OldName', 'OldLast', 'OldMiddle');
        $anEmployee = $this->employeeBuilder->withName($oldName)->build();
        $newName = new Name('NewName', 'NewLast', 'NewMiddle');

        $anEmployee->changeName($newName);

        $this->assertInstanceOf(Name::class, $anEmployee->getName());
        $this->assertSame($newName, $anEmployee->getName());
    }

    public function test_can_change_birthdate()
    {
        $oldBirthDate = \DateTime::createFromFormat('Y-m-d', '1995-01-01');
        $anEmployee = $this->employeeBuilder->withBirthDate($oldBirthDate)->build();
        $newBirthDate = \DateTime::createFromFormat('Y-m-d', '1995-01-10');

        $anEmployee->changeBirthDate($newBirthDate);

        $this->assertInstanceOf(\DateTime::class, $anEmployee->getBirthdate());
        $this->assertSame($newBirthDate, $anEmployee->getBirthdate());
    }

    public function test_can_change_gender()
    {
        $gender = new Gender('male');
        $anEmployee  = $this->employeeBuilder->withGender($gender)->build();
        $newGender = new Gender('female');

        $anEmployee->changeGender($newGender);

        $this->assertInstanceOf(Gender::class, $anEmployee->getGender());
        $this->assertSame($newGender, $anEmployee->getGender());
    }
}
