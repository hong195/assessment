<?php


namespace Domain\Model\User;

final class User
{
    /**
     * @var UserId
     */
    private UserId $id;

    /**
     * @var string
     */
    private $name;
    /**
     * @var Role
     */
    private Role $role;
    /**
     * @var Login
     */
    private Login $login;

    public function __construct(UserId $userId, Login $login, FullName $name, Role $role)
    {
        $this->id = $userId;
        $this->login = $login;
        $this->name = $name;
        $this->role = $role;
    }

    /**
     * @return UserId
     */
    public function getId(): UserId
    {
        return $this->id;
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getFullName(): FullName
    {
        return $this->name;
    }
}
