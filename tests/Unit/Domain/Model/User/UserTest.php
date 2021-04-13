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
    public function test_can_create_a_user()
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
}
