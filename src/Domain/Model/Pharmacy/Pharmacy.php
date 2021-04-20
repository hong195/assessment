<?php


namespace Domain\Model\Pharmacy;


use Doctrine\Common\Collections\ArrayCollection;
use Domain\Model\User\FullName;
use Domain\Model\User\Login;
use Domain\Model\User\Role;
use Domain\Model\User\User;
use Domain\Model\User\UserId;

final class Pharmacy
{
    private PharmacyId $pharmacyId;

    private Email $email;

    private ArrayCollection $employees;

    private PharmacyNumber $number;

    public function __construct(PharmacyId $pharmacyId, PharmacyNumber $number, Email $email)
    {
        $this->email = $email;
        $this->employees = new ArrayCollection([]);
        $this->pharmacyId = $pharmacyId;
        $this->number = $number;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getUsers(): ArrayCollection
    {
        return $this->employees;
    }

    public function addUser(UserId $userId, Login $login, FullName $fullName): User
    {
        $user = new User($userId, $login, $fullName, new Role(Role::PHARMACIST));

        $this->employees->add($user);

        return $user;
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
        return $this->pharmacyId;
    }

    public function getNumber(): PharmacyNumber
    {
        return $this->number;
    }
}
