<?php


namespace Domain\Model\Pharmacy;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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

    private ArrayCollection $employees;

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
}
