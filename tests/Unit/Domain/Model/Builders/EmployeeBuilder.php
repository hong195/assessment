<?php


namespace Tests\Unit\Domain\Model\Builders;


use Domain\Model\Employee\Employee;
use Domain\Model\Employee\EmployeeId;
use Domain\Model\Employee\Gender;
use Domain\Model\Employee\Name;
use Domain\Model\Pharmacy\Pharmacy;

class EmployeeBuilder
{
    private EmployeeId $id;
    private Name $name;
    private Pharmacy $pharmacy;
    /**
     * @var \DateTime|false
     */
    private $birthday;

    private Gender $gender;

    public function __construct()
    {
        $this->id = new EmployeeId(EmployeeId::next());
        $this->name = new Name('First', 'Last', 'Middle');
        $this->pharmacy = PharmacyBuilder::aPharmacy()->build();
        $this->birthday = \DateTime::createFromFormat('Y-m-d', '2000-12-01');
        $this->gender = new Gender('male');
    }

    public function withName(Name $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function withBirthDate(\DateTime $birthDate): self
    {
        $this->birthday = $birthDate;
        return $this;
    }

    public function withGender(Gender $gender) : self
    {
        $this->gender = $gender;

        return $this;
    }

    public static function anEmployee(): EmployeeBuilder
    {
        return new self();
    }

    public function build(): Employee
    {
        return new Employee($this->id, $this->pharmacy, $this->name, $this->birthday, $this->gender);
    }
}
