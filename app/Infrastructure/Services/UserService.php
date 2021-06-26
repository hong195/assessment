<?php


namespace App\Infrastructure\Services;


use App\Exceptions\NotFoundEntityException;
use App\Domain\Model\User\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Model\User\FullName;
use App\Domain\Model\User\Login;
use App\Domain\Model\User\PasswordHasher;
use App\Domain\Model\User\Role;
use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserRepository;
use App\Exceptions\LoginHasBeenAlreadyTakenException;

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
     */
    public function addUser(UserId $id, Login $login, string $unHashedPassword, FullName $fullName, Role $role)
    {
        if ($this->repository->findByLogin($login)) {
            throw new LoginHasBeenAlreadyTakenException();
        }

        $hashedPassword = $this->hasher->hash($unHashedPassword);

        $user = new User($id, $login, $hashedPassword, $fullName, $role);

        $this->repository->add($user);
        $this->em->flush();
    }

    /**
     * @throws NotFoundEntityException|LoginHasBeenAlreadyTakenException
     */
    public function updateUser(UserId $id, Login $login, FullName $fullName, Role $role, string $unHashedPassword = null)
    {
        $user = $this->repository->findOrFail($id);

        if ($this->repository->findByLogin($login)) {
            throw new LoginHasBeenAlreadyTakenException();
        }

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

    public function deleteUser(UserId $id)
    {
        $user = $this->repository->findOrFail($id);

        $this->repository->remove($user);
        $this->em->flush();
    }
}
