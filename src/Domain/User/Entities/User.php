<?php


namespace Domain\User\Entities;


use Domain\Check\Entities\Check;
use Domain\Check\ValueObjects\CreationDate;
use Domain\User\Exception\SubscriberCantCreateCheckException;
use Domain\User\Exception\TheSameUserException;
use Domain\User\ValueObjects\Email;
use Domain\User\ValueObjects\FullName;
use Domain\User\ValueObjects\Role;
use Domain\User\ValueObjects\UserId;

class User
{
    /**
     * @var UserId
     */
    private $id;
    /**
     * @var Email
     */
    private $email;
    /**
     * @var string
     */
    private $name;
    /**
     * @var Role
     */
    private $role;

    public function __construct(UserId $userId, Email $email, FullName $name, Role $role = null)
    {
        $this->id = $userId;
        $this->email = $email;
        $this->name = $name;
        $this->role = $role ?? new Role(Role::SUBSCRIBER);
    }

    /**
     * @return UserId
     */
    public function getId(): UserId
    {
        return $this->id;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @return FullName
     */
    public function getFullName(): FullName
    {
        return $this->name;
    }

    /**
     * @param User $user
     * @param CreationDate $creationDate
     * @param array $criteria
     * @return Check
     * @throws TheSameUserException|SubscriberCantCreateCheckException
     */
    public function addCheck(User $user, CreationDate $creationDate, array $criteria): Check
    {
        if ($this->getRole()->isEqualsTo(Role::SUBSCRIBER)) {
            throw new SubscriberCantCreateCheckException;
        }

        return new Check($user, $this, $creationDate, $criteria);
    }
}
