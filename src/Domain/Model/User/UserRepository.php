<?php


namespace Domain\Model\User;


interface UserRepository
{
    public function findById(UserId $userId) : ?User;

    public function findOrFail(UserId $userId) : User;

    public function remove(UserId $userId) : void;

    public function add(User $user);

    public function getAll();

    public function findByIds(array $ids);
}
