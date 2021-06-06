<?php


namespace Tests\Feature\Infastructure\Services;


use Doctrine\ORM\EntityManagerInterface;
use Domain\Exceptions\NotFoundEntityException;
use Domain\Model\User\FullName;
use Domain\Model\User\Login;
use Domain\Model\User\PasswordHasher;
use Domain\Model\User\Role;
use Domain\Model\User\UserId;
use Domain\Model\User\UserRepository;
use Infastructure\Exceptions\LoginHasBeenAlreadyTakenException;
use Infastructure\Services\UserService;
use Tests\Feature\DoctrineMigrationsTrait;
use Tests\TestCase;
use Tests\Unit\Domain\Model\Builders\UserBuilder;

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
        $id = UserId::next();
        $login = new Login('user-login');
        $name = new FullName('first', 'last', 'middle');
        $role = new Role(Role::PHARMACIST);
        $password = 'user-pass';

        $this->userService->addUser($id, $login, $password, $name, $role);
        $createdUser = $this->repository->findById($id);

        $this->assertEquals((string) $id, $createdUser->getId());
        $this->assertEquals((string) $login, $createdUser->getLogin());
        $this->assertEquals((string) $name, $createdUser->getFullName());
        $this->assertEquals((string) $role, $createdUser->getRole());
    }

    public function test_user_login_must_be_unique()
    {
        $login = new Login('user-login');
        $user = UserBuilder::aUser()->withLogin($login)->build();

        $this->repository->add($user);
        $this->em->flush();

        $id = UserId::next();
        $name = new FullName('first', 'last', 'middle');
        $role = new Role(Role::PHARMACIST);
        $password = 'user-pass';

        $this->expectException(LoginHasBeenAlreadyTakenException::class);

        $this->userService->addUser($id, $login, $password, $name, $role);
    }

    public function test_can_update_user()
    {
        $id = UserId::next();
        $oldLogin = new Login('user-login');
        $oldName = new FullName('first', 'last', 'middle');
        $oldRole = new Role(Role::PHARMACIST);
        $oldPassword = 'user-pass';
        $this->userService->addUser($id, $oldLogin, $oldPassword, $oldName, $oldRole);

        $newLogin = new Login('new-user-login');
        $newName = new FullName('new-first', 'new-last', 'new-middle');
        $newRole = new Role(Role::ADMIN);
        $newPassword = 'new-user-pass';
        $this->userService->updateUser($id, $newLogin, $newName, $newRole, $newPassword);

        $found = $this->repository->findById($id);

        $this->assertEquals((string) $id, $found->getId());
        $this->assertEquals((string) $newLogin, $found->getLogin());
        $this->assertEquals((string) $newName, $found->getFullName());
        $this->assertEquals((string) $newRole, $found->getRole());
    }

    public function test_cannot_update_non_existing_user()
    {
        $id = UserId::next();
        $login = new Login('login');
        $name = new FullName('first', 'last', 'middle');
        $role = new Role(Role::PHARMACIST);
        $password = 'user-pass';

        $this->expectException(NotFoundEntityException::class);

        $this->userService->updateUser($id, $login, $name, $role, $password);
    }

    public function test_cannot_delete_non_existing_user()
    {
        $aUser = UserBuilder::aUser()->build();

        $this->expectException(NotFoundEntityException::class);

        $this->userService->deleteUser($aUser->getId());
    }

    public function test_can_delete_a_user()
    {
        $aUser = UserBuilder::aUser()->build();
        $this->repository->add($aUser);
        $this->em->flush();

        $this->userService->deleteUser($aUser->getId());

        $this->assertDatabaseCount('users', 0);
    }
}
