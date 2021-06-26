<?php


namespace Tests\Builders;


use App\Domain\Model\Employee\Employee;
use App\Domain\Model\Employee\EmployeeId;
use App\Domain\Model\Employee\Gender;
use App\Domain\Model\Employee\Name;
use App\Domain\Model\Pharmacy\Pharmacy;

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

    public function withId(EmployeeId $id) : self
    {
        $this->id = $id;
        return $this;
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

    public function withPharmacy(Pharmacy $pharmacy) : self
    {
        $this->pharmacy = $pharmacy;
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
