<?php


namespace App\Domain\Model\Employee;

use Doctrine\ORM\Mapping as ORM;
use App\Domain\Model\Pharmacy\Pharmacy;

/**
 * Class Employee
 * @ORM\Entity
 */
class Employee
{
    /**
     * @ORM\Column (type="employee_id")
     * @ORM\Id
     */
    private EmployeeId $id;
    /**
     * @ORM\Embedded
     */
    private Name $name;
    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Pharmacy\Pharmacy", inversedBy="employees")
     * @ORM\JoinColumn(name="pharmacy_id", referencedColumnName="id")
     */
    private Pharmacy $pharmacy;
    /**
     * @ORM\Column (type="datetime")
     */
    private \DateTime $birthdate;
    /**
     * @ORM\Embedded
     */
    private Gender $gender;

    /**
     * Employee constructor.
     * @param EmployeeId $id
     * @param Pharmacy $pharmacy
     * @param Name $name
     * @param \DateTime $birthdate
     * @param Gender $gender
     */
    public function __construct(EmployeeId $id,
                                Pharmacy $pharmacy,
                                Name $name,
                                \DateTime $birthdate,
                                Gender $gender)
    {
        $this->id = $id;
        $this->name = $name;
        $this->pharmacy = $pharmacy;
        $this->birthdate = $birthdate;
        $this->gender = $gender;
    }

    public function getId(): EmployeeId
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function changeName(Name $name)
    {
        $this->name = $name;
    }

    public function getBirthdate(): \DateTime
    {
        return $this->birthdate;
    }

    public function changeBirthDate(\DateTime $birthDate)
    {
        $this->birthdate = $birthDate;
    }

    public function getGender(): Gender
    {
        return $this->gender;
    }

    public function changeGender(Gender $gender)
    {
        $this->gender = $gender;
    }

    public function changePharmacy(Pharmacy $pharmacy)
    {
        $this->pharmacy = $pharmacy;
    }

    public function getPharmacy(): Pharmacy
    {
        return $this->pharmacy;
    }
}
