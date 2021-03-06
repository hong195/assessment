<?php


namespace App\Domain\Model\Pharmacy;


use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Domain\Model\Employee\Employee;

/**
 * @ORM\Entity
 */
class Pharmacy
{
    /**
     * @ORM\Column(type="pharmacy_id")
     * @ORM\Id
     */
    private PharmacyId $id;

    /**
     * @ORM\Embedded (class="Email")
     */
    private Email $email;
    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Model\Employee\Employee", mappedBy="pharmacy", cascade={"persist","remove"})
     */
    private Collection $employees;

    /**
     * @ORM\Embedded (class="PharmacyNumber")
     */
    private PharmacyNumber $number;

    public function __construct(PharmacyId $pharmacyId, PharmacyNumber $number, Email $email)
    {
        $this->email = $email;
        $this->employees = new ArrayCollection([]);
        $this->id = $pharmacyId;
        $this->number = $number;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function changeEmail(Email $email)
    {
        $this->email = $email;
    }

    public function changeNumber(PharmacyNumber $number)
    {
        $this->number = $number;
    }

    public function getId(): PharmacyId
    {
        return $this->id;
    }

    public function getNumber(): PharmacyNumber
    {
        return $this->number;
    }

    public function getEmployees() : Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee)
    {
        $this->employees->add($employee);
    }

    public function resign(Employee $employee)
    {
        foreach ($this->employees as $key => $pharmacyEmployee) {
            if ($employee->getId()->isEqual($pharmacyEmployee->getId())) {
                $this->employees->remove($key);
                break;
            }
        }
    }
}
