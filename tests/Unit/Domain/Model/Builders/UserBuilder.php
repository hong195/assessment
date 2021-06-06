<?php


namespace Tests\Unit\Domain\Model\Builders;


use Domain\Model\User\FullName;
use Domain\Model\User\Login;
use Domain\Model\User\Role;
use Domain\Model\User\User;
use Domain\Model\User\UserId;
use Infastructure\Services\CustomPasswordHasher;

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
    private string $password;
    private CustomPasswordHasher $passHasher;

    public function __construct()
    {
        $this->id = UserId::next();
        $this->login = new Login('user-login');
        $this->fullName = new FullName('Test', 'Test', 'Test');
        $this->role = new Role(Role::PHARMACIST);
        $this->passHasher = new CustomPasswordHasher();
        $this->password = $this->passHasher->hash('test');
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

    public function withPassword(string $password): UserBuilder
    {
        $this->password = $this->passHasher->hash($password);
        return $this;
    }

    public function build(): User
    {
        return new User($this->id, $this->login, $this->password, $this->fullName, $this->role);
    }
}
