<?php


namespace Infastructure\Persistence\Doctrine;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Model\User\User;
use Domain\Model\User\UserId;
use Domain\Model\User\UserRepository;

final class DoctrineUserRepository implements UserRepository
{
    private EntityManager $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findById(UserId $userId): ?User
    {
        dd($this->em->getRepository(User::class)->find($userId));
        return $this->em->getRepository(User::class)->find($userId);
    }

    public function findOrFail(UserId $userId): User
    {
        // TODO: Implement findOrFail() method.
    }

    public function remove(UserId $userId): void
    {
        // TODO: Implement remove() method.
    }

    public function add(User $user)
    {
        // TODO: Implement add() method.
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function findByIds(array $ids)
    {
        // TODO: Implement findByIds() method.
    }
}
