<?php

namespace App\Domain\Model\User;


use Doctrine\ORM\Mapping as ORM;
use \Illuminate\Contracts\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @ORM\Entity
 */
class User implements Authenticatable, JWTSubject
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
     * @ORM\Column (type="string")
     */
    private string $password;

    public function __construct(UserId $userId, Login $login, $password, FullName $name, Role $role)
    {
        $this->id = $userId;
        $this->login = $login;
        $this->name = $name;
        $this->role = $role;
        $this->password = $password;
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

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getAuthPassword(): string
    {
        return $this->getPassword();
    }

    public function getJWTIdentifier()
    {
        return $this->getId();
    }

    public function getJWTCustomClaims() : array
    {
        return [];
    }

    public function getRememberToken(){}

    public function setRememberToken($value){}

    public function getRememberTokenName(){}
}
