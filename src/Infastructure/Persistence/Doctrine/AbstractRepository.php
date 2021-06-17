<?php


namespace Infastructure\Persistence\Doctrine;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

abstract class AbstractRepository
{
    protected EntityManagerInterface $em;
    protected \Doctrine\Persistence\ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager, string $entity)
    {
        $this->em = $entityManager;
        $this->repository = $this->em->getRepository($entity);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findOrFail($id): ?object
    {
        if (!$entity = $this->repository->find($id)) {
            throw new EntityNotFoundException;
        }

        return $entity;
    }

    public function all()
    {
        return $this->repository->findAll();
    }
}
