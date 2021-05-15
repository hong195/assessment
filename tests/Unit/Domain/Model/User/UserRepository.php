<?php


namespace Tests\Unit\Domain\Model\User;


use App\Models\User;
use Domain\Model\User\FullName;
use Domain\Model\User\Login;
use Domain\Model\User\Role;
use Domain\Model\User\UserId;

interface UserRepository
{
    public function findById(UserId $userId) : User;

    public function add(UserId $userId, Login $login, FullName $name, Role $role);

    public function remove(UserId $userId);

    public function save(User $user);
}
