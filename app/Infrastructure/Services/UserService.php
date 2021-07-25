<?php


namespace App\Infrastructure\Services;


use App\DataTransferObjects\UserDto;
use App\Domain\Model\User\FullName;
use App\Domain\Model\User\Login;
use App\Domain\Model\User\PasswordHasher;
use App\Domain\Model\User\Role;
use App\Domain\Model\User\User;
use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserRepository;
use App\Exceptions\LoginHasBeenAlreadyTakenException;
use App\Exceptions\NotFoundEntityException;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private UserRepository $repository;
    private EntityManagerInterface $em;
    private PasswordHasher $hasher;

    public function __construct(UserRepository $repository, EntityManagerInterface $em, PasswordHasher $hasher)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->hasher = $hasher;
    }

    /**
     * @throws LoginHasBeenAlreadyTakenException
     * @throws \App\Exceptions\InvalidRoleException
     */
    public function addUser(UserDto $userDto)
    {
        $login = new Login($userDto->getLogin());

        if ($this->repository->findByLogin($login)) {
            throw new LoginHasBeenAlreadyTakenException();
        }

        $fullName = new FullName($userDto->getName(), $userDto->getLastName(), $userDto->getMiddleName());
        $role = new Role($userDto->getRole());
        $hashedPassword = $this->hasher->hash($userDto->getPassword());

        $user = new User(UserId::next(), $login, $hashedPassword, $fullName, $role);

        $this->repository->add($user);
        $this->em->flush();
    }

    /**
     * @throws NotFoundEntityException|LoginHasBeenAlreadyTakenException
     */
    public function updateUser(string $userId, UserDto $userDto)
    {
        /** @var User $user */

        $user = $this->repository->findOrFail($userId);
        $login = new Login($userDto->getLogin());

        if ((string) $user->getLogin() !== (string) $login) {
            $login = $this->repository->findByLogin($login);

            if ($login) {
                throw new LoginHasBeenAlreadyTakenException();
            }
        }

        $fullName = new FullName($userDto->getName(), $userDto->getLastName(), $userDto->getMiddleName());
        $role = new Role($userDto->getRole());
        $unHashedPassword = $userDto->getPassword();

        $user->updateName($fullName);
        $user->changeLogin($login);
        $user->setRole($role);

        if ($unHashedPassword) {
            $hashedPassword = $this->hasher->hash($unHashedPassword);
            $user->setPassword($hashedPassword);
        }

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @throws NotFoundEntityException
     */
    public function deleteUser(string $id)
    {
        $userId = new UserId($id);

        $user = $this->repository->findOrFail($userId);

        $this->repository->remove($user);
        $this->em->flush();
    }
}
