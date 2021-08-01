<?php


namespace App\Domain\Model\User;


use App\Domain\Shared\AbstractRepository;
use Doctrine\Common\Collections\ArrayCollection;

interface UserRepository extends AbstractRepository
{
    public function findById(int $userId) : ?User;

    public function remove(User $user) : void;

    public function add(User $user) : void;

    public function getAll();

    public function findByIds(array $ids) : ArrayCollection;

    public function findByLogin(Login $login) : ?User;
}
