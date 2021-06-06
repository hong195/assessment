<?php

namespace Domain\Model\User;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 */
class User
{
    /**
     * @ORM\Column(type="user_id")
     * @ORM\Id
     */
    private UserId $id;
    /**
     * @ORM\Embedded(class="FullName")
     */
    private FullName $name;
    /**
     * @ORM\Embedded(class="Role")
     */
    private Role $role;
    /**
     * @ORM\Embedded(class="Login")
     */
    private Login $login;

    /**
     * @ORM\Column (typre="string")
     */
    private string $password;

    public function __construct(UserId $userId, Login $login, FullName $name, Role $role)
    {
        $this->id = $userId;
        $this->login = $login;
        $this->name = $name;
        $this->role = $role;
        $this->password = '';
    }

    public function changeLogin(Login $login)
    {
        $this->login = $login;
    }

    public function updateName(FullName $name)
    {
        $this->name = $name;
    }

    public function setRole(Role $role)
    {
        $this->role = $role;
    }

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

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPassword() : string
    {
        return $this->password;
    }
}
