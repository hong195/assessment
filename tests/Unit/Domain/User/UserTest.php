<?php

namespace Tests\Unit\Domain\User;

use Domain\User\Entities\User;
use Domain\User\ValueObjects\Email;
use Domain\User\ValueObjects\FullName;
use Domain\User\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @var UserId
     */
    private $userId;
    /**
     * @var Email
     */
    private $email;
    /**
     * @var FullName
     */
    private $name;
    /**
     * @var User
     */
    private $user;

    private $firstName = 'Alexey';

    private $lastName = 'Em';

    private $patronymic = 'Aleksandrovich';

    protected function setUp(): void
    {
        parent::setUp();

        $this->userId = new UserId(123);
        $this->email = new Email('alexeyhong10@gmail.com');
        $this->name = new FullName($this->firstName, $this->lastName, $this->patronymic);

        $this->user = new User($this->userId, $this->email, $this->name);
    }

    public function test_can_get_user_information()
    {
        $userId = 123;
        $fullName = $this->firstName . ' ' . $this->lastName . ' ' . $this->patronymic;

        $this->assertEquals($fullName, (string) $this->user->getFullName());
        $this->assertEquals($userId, (string) $this->user->getId());
        $this->assertEquals((string) $this->email, (string) $this->user->getEmail());

        $this->assertInstanceOf(UserId::class, $this->user->getId());
        $this->assertInstanceOf(Email::class, $this->user->getEmail());
        $this->assertInstanceOf(FullName::class, $this->user->getFullName());
    }

    public function test_can_create_user()
    {
        $this->assertInstanceOf(User::class, $this->user);
    }
}
