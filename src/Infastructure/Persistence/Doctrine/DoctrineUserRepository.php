<?php


namespace Infastructure\Persistence\Doctrine;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Exceptions\NotFoundEntityException;
use Domain\Model\User\User;
use Domain\Model\User\UserId;
use Domain\Model\User\UserRepository;
use \Doctrine\Persistence\ObjectRepository;
use Illuminate\Support\Arr;

final class DoctrineUserRepository implements UserRepository
{
    private EntityManagerInterface $em;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(User::class);
        $this->em = $em;
    }

    public function findById(UserId $userId): ?User
    {
        return $this->repository->find($userId);
    }

    /**
     * @param UserId $userId
     * @return User
     * @throws NotFoundEntityException
     */
    public function findOrFail(UserId $userId): User
    {
        $user = $this->repository->find($userId);

        if (!$user) {
            throw new NotFoundEntityException();
        }

        return $user;
    }

    public function remove(User $user): void
    {
        $this->em->remove($user);
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }

    public function getAll(): ArrayCollection
    {
        return new ArrayCollection($this->repository->findAll());
    }

    public function findByIds(array $ids): ArrayCollection
    {
        $qb = $this->repository->createQueryBuilder('u');
        $query = $qb->select('u')->add('where', $qb->expr()->in('u.id', $ids))->getQuery();

        return new ArrayCollection($query->getResult());
    }
}
