<?php


namespace App\Infrastructure\Persistence\Doctrine;


use Doctrine\ORM\EntityManagerInterface;
use App\Exceptions\NotFoundEntityException;

abstract class AbstractRepository
{
    protected EntityManagerInterface $em;
    protected \Doctrine\Persistence\ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager, string $entity)
    {
        $this->em = $entityManager;
        $this->repository = $this->em->getRepository($entity);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }
    /**
     * @throws NotFoundEntityException
     */
    public function findOrFail($id): ?object
    {
        if (!$entity = $this->repository->find($id)) {
            throw new NotFoundEntityException();
        }

        return $entity;
    }

    public function all()
    {
        return $this->repository->findAll();
    }
}
