<?php


namespace Tests\Unit\Domain\Model\Builders;


use Domain\Model\User\FullName;
use Domain\Model\User\Login;
use Domain\Model\User\Role;
use Domain\Model\User\User;
use Domain\Model\User\UserId;

class UserBuilder
{
    private UserId $id;
    /**
     * @var Login
     */
    private Login $login;
    /**
     * @var Role
     */
    private Role $role;

    private FullName $fullName;

    public function __construct()
    {
        $this->id = UserId::next();
        $this->login = new Login('user-login');
        $this->fullName = new FullName('Test', 'Test', 'Test');
        $this->role = new Role(Role::PHARMACIST);
    }

    public static function aUser(): UserBuilder
    {
        return new self();
    }

    public function withId(UserId $userId): UserBuilder
    {
        $this->id = $userId;
        return $this;
    }

    public function withLogin(Login $login): UserBuilder
    {
        $this->login = $login;
        return $this;
    }

    public function withName(FullName $fullName): UserBuilder
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function withRole(Role $role): UserBuilder
    {
        $this->role = $role;

        return $this;
    }

    public function build(): User
    {
        return new User($this->id, $this->login, $this->fullName, $this->role);
    }
}
