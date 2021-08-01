<?php


namespace Tests\Builders;


use App\Domain\Model\User\FullName;
use App\Domain\Model\User\Login;
use App\Domain\Model\User\Role;
use App\Domain\Model\User\User;
use App\Domain\Model\User\UserId;
use App\Infrastructure\Services\CustomPasswordHasher;

class UserBuilder
{
    private int $id;
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
        $this->login = new Login('user-login');
        $this->fullName = new FullName('Test', 'Test', 'Test');
        $this->role = new Role(Role::EDITOR);
        $this->passHasher = new CustomPasswordHasher();
        $this->password = $this->passHasher->hash('test');
    }

    public static function aUser(): UserBuilder
    {
        return new self();
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
        return new User($this->login, $this->password, $this->fullName, $this->role);
    }
}
