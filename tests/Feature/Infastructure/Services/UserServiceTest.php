<?php


namespace Tests\Feature\Infastructure\Services;


use App\DataTransferObjects\UserDto;
use Doctrine\ORM\EntityManagerInterface;
use App\Exceptions\NotFoundEntityException;
use App\Domain\Model\User\FullName;
use App\Domain\Model\User\Login;
use App\Domain\Model\User\PasswordHasher;
use App\Domain\Model\User\Role;
use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserRepository;
use App\Exceptions\LoginHasBeenAlreadyTakenException;
use App\Infrastructure\Services\UserService;
use Tests\Feature\DoctrineMigrationsTrait;
use Tests\TestCase;
use Tests\Builders\UserBuilder;

/**
 * @group integration
 */
class UserServiceTest extends TestCase
{
    use DoctrineMigrationsTrait;

    private UserRepository $repository;

    private EntityManagerInterface $em;

    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resetMigrations();
        $this->repository = app()->make(UserRepository::class);
        $this->em = app()->make(EntityManagerInterface::class);
        $hasher = app()->make(PasswordHasher::class);
        $this->userService = new UserService($this->repository, $this->em, $hasher);
    }

    public function test_create_a_user()
    {
        $this->userService->addUser(new UserDto(
            'Jhon',
            'Doe',
            'Smith',
            'user-login',
            Role::REVIEWER,
            'user-pass'
        ));

        $createdUser = $this->repository->findByLogin(new Login('user-login'));

        $this->assertEquals('user-login', $createdUser->getLogin());
        $this->assertEquals('Jhon Doe Smith', $createdUser->getFullName());
        $this->assertEquals(Role::REVIEWER, $createdUser->getRole());
    }

    public function test_user_login_must_be_unique()
    {
        $duplicateLogin = new Login('user-login');
        $user = UserBuilder::aUser()->withLogin($duplicateLogin)->build();

        $this->repository->add($user);
        $this->em->flush();

        $this->expectException(LoginHasBeenAlreadyTakenException::class);

        $this->userService->addUser(new UserDto(
            'Jhon',
            'Doe',
            'Smith',
            (string) $duplicateLogin,
            Role::REVIEWER,
            'user-pass'
        ));
    }

    public function test_can_update_user()
    {
        $this->userService->addUser(new UserDto(
            'Jhon',
            'Doe',
            'Smith',
            'old-login',
            Role::REVIEWER,
            'user-pass'
        ));

        $found = $this->repository->findByLogin(new Login('old-login'));

        $this->userService->updateUser((string) $found->getId(), new UserDto(
            'Tim',
            'Cook',
            'Cooper',
            'new-login',
            Role::ADMIN
        ));

        $this->assertEquals('new-login', $found->getLogin());
        $this->assertEquals('Tim Cook Cooper', $found->getFullName());
        $this->assertEquals(Role::ADMIN, $found->getRole());
    }

    public function test_cannot_update_non_existing_user()
    {
        $notExistingId = 1000;
        $login = new Login('login');
        $name = new FullName('first', 'last', 'middle');

        $this->expectException(NotFoundEntityException::class);

        $userDto = new UserDto(
            $name->firstName(),
            (string) $name->lastName(),
            $name->patronymic(),
            (string) $login,
            Role::REVIEWER,
            'user-pass'
        );

        $this->userService->updateUser($notExistingId, $userDto);
    }

    public function test_cannot_delete_non_existing_user()
    {
        $notExistingUserId = 1000000;

        $this->expectException(NotFoundEntityException::class);

        $this->userService->deleteUser($notExistingUserId);
    }

    public function test_can_delete_a_user()
    {
        $aUser = UserBuilder::aUser()->build();
        $this->repository->add($aUser);
        $this->em->flush();

        $this->userService->deleteUser($aUser->getId());
        $foundUsers = $this->repository->getAll();

        $this->assertDatabaseCount('users', 0);
        $this->assertEmpty($foundUsers);
    }
}
