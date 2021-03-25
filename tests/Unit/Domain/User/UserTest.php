<?php

namespace Tests\Unit\Domain\User;

use Domain\Check\Entities\Check;
use Domain\Check\ValueObjects\CreationDate;
use Domain\User\Entities\User;
use Domain\User\Exception\InvalidRoleException;
use Domain\User\Exception\SubscriberCantCreateCheckException;
use Domain\User\Exception\TheSameUserException;
use Domain\User\ValueObjects\Email;
use Domain\User\ValueObjects\FullName;
use Domain\User\ValueObjects\Role;
use Domain\User\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_can_get_user_information()
    {
        $user = $this->createUser(UserId::next());

        $this->assertInstanceOf(UserId::class, $user->getId());
        $this->assertInstanceOf(Email::class, $user->getEmail());
        $this->assertInstanceOf(FullName::class, $user->getFullName());
    }

    public function test_the_reviewer_can_create_check()
    {
        $reviewer = $this->createUser(UserId::next(), Role::REVIEWER);
        $user = $this->createUser();
        $creationDate = new CreationDate(now());
        $check = $reviewer->addCheck($user, $creationDate, []);

        $this->assertInstanceOf(Check::class, $check);
        $this->assertEquals($creationDate, $check->getCreationDate());
    }

    public function test_expects_exception_when_reviewer_tries_to_check_himself()
    {
        $reviewer = $this->createUser(UserId::next(), Role::REVIEWER);

        $this->expectException(TheSameUserException::class);

        $reviewer->addCheck($reviewer, new CreationDate(now()), []);
    }

    public function test_successful_user_creation_with_reviewer_or_subscriber_role()
    {
        $user1 = $this->createUser(UserId::next());
        $user2 = $this->createUser(UserId::next(), Role::REVIEWER);

        $this->assertTrue($user1->getRole()->isEqualsTo(Role::SUBSCRIBER));
        $this->assertTrue($user2->getRole()->isEqualsTo(Role::REVIEWER));
    }

    public function test_expects_exception_when_create_user_with_non_existing_role()
    {
        $this->expectException(InvalidRoleException::class);

        $this->createUser(UserId::next(), 'non-existing-role');
    }

    public function test_expects_exception_when_subscriber_creates_check()
    {
        $subscriber = $this->createUser(UserId::next());
        $subscriber2 = $this->createUser();

        $this->expectException(SubscriberCantCreateCheckException::class);

        $subscriber->addCheck($subscriber2, new CreationDate(now()), []);
    }

    private function createCheck(User $user, User $reviewer, array $criteria = []): Check
    {
        return new Check(
            $user,
            $reviewer,
            new CreationDate(now()),
            $criteria,
        );
    }

    /**
     * @param null $uiId
     * @param string $role
     * @return User
     * @throws \Domain\User\Exception\InvalidRoleException
     */
    private function createUser($uiId = null, $role = Role::SUBSCRIBER): User
    {
        if (!$uiId) {
            $uiId = UserId::next();
        }

        return new User(
            new UserId($uiId),
            new Email('test@gmail.com'),
            new FullName('Alexey', 'Em', 'Aleksandrovich'),
            new Role($role)
        );
    }
}
