<?php


namespace Domain\Model\User;


use Doctrine\Common\Collections\ArrayCollection;

interface UserRepository
{
    public function findById(UserId $userId) : ?User;

    public function findOrFail(UserId $userId) : User;

    public function remove(User $user) : void;

    public function add(User $user) : void;

    public function getAll();

    public function findByIds(array $ids) : ArrayCollection;

    public function findByLogin(Login $login) : ?User;
}
