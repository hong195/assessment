<?php


namespace Tests\Unit\Domain\Model\User;

use Domain\Model\User\FullName;
use Domain\Model\User\Login;
use Domain\Model\User\Role;
use Domain\Model\User\UserId;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Domain\Model\Builders\UserBuilder;


class UserTest extends TestCase
{
    public function test_user()
    {
        $userId = new UserId(UserId::next());
        $user = UserBuilder::aUser()
            ->withId($userId)
            ->withLogin(new Login('user-login'))
            ->withName(new FullName('Alex', 'Patrick', 'Black'))
            ->withRole(new Role(Role::PHARMACIST))
            ->build();

        $this->assertInstanceOf(UserId::class, $user->getId());
        $this->assertInstanceOf(Login::class, $user->getLogin());
        $this->assertInstanceOf(FullName::class, $user->getFullName());

        $this->assertEquals((string) $userId, (string) $user->getId());
        $this->assertEquals('user-login', (string) $user->getLogin());
        $this->assertEquals('Alex Patrick Black', (string) $user->getFullName());
    }

    public function test_can_change_login()
    {
        $user = UserBuilder::aUser()->withLogin(new Login('old-login'))->build();
        $newLogin = new Login('new-login');

        $user->changeLogin($newLogin);

        $this->assertEquals($newLogin, $user->getLogin());
        $this->assertEquals('new-login', (string) $user->getLogin());
    }

    public function test_update_full_name()
    {
        $user = UserBuilder::aUser()->withName(new FullName('oldName', 'oldLastName'))->build();
        $newName = new FullName('newName', 'newLastName');

        $user->updateName($newName);

        $this->assertEquals($newName, $user->getFullName());
        $this->assertEquals('newName', (string) $user->getFullName()->firstName());
        $this->assertEquals('newLastName', (string) $user->getFullName()->lastName());
    }

    public function test_get_password()
    {
        $pass = 'user-pass';
        $aUser = UserBuilder::aUser()->build();

        $aUser->setPassword($pass);

        $this->assertEquals($aUser->getPassword(), $pass);
    }
}
