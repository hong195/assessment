<?php

namespace Tests\Feature\Infastructure\Persistence;

use App\Exceptions\DomainException;
use App\Domain\Model\User\Login;
use App\Domain\Model\User\User;
use App\Domain\Model\User\UserRepository;
use Tests\TestCase;
use Tests\Builders\UserBuilder;

abstract class AbstractUserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->getRepository();
    }

    abstract protected function getRepository(): UserRepository;

    public function test_add_user()
    {
        $login = new Login('login');
        $aUser = UserBuilder::aUser()->withLogin($login)->build();
        $this->userRepository->add($aUser);
        $addedUser = $this->userRepository->findByLogin($login);

        $this->assertNotEmpty($this->userRepository->getAll());
        $this->assertInstanceOf(User::class, $addedUser);
        $this->assertSame($aUser, $addedUser);
    }

    public function test_find_or_fail()
    {
        $this->expectException(DomainException::class);

        $this->userRepository->findOrFail(1000000);
    }

    public function test_remove()
    {
        $aUser = UserBuilder::aUser()->build();
        $aUser2 = UserBuilder::aUser()->build();

        $this->userRepository->add($aUser);
        $this->userRepository->add($aUser2);
        $this->userRepository->remove($aUser);

        $this->assertNotContains($aUser, $this->userRepository->getAll());
        $this->assertCount(1, $this->userRepository->getAll());
    }

    public function test_get_all()
    {
        $aUser = UserBuilder::aUser()->build();
        $aUser2 = UserBuilder::aUser()->build();

        $this->userRepository->add($aUser);
        $this->userRepository->add($aUser2);

        $this->assertNotEmpty($this->userRepository->getAll());
        $this->assertContains($aUser, $this->userRepository->getAll());
        $this->assertContains($aUser2, $this->userRepository->getAll());
    }

    public function test_can_find_by_login()
    {
        $login = new Login('login');
        $aUser = UserBuilder::aUser()->withLogin($login)->build();

        $this->userRepository->add($aUser);
        $foundUser = $this->userRepository->findByLogin($login);

        $this->assertEquals((string)$login, (string)$foundUser->getLogin());
    }
}
