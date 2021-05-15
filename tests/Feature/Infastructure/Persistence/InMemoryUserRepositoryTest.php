<?php

namespace Tests\Feature\Infastructure\Persistence;


use Domain\Model\User\UserRepository;
use Infastructure\Persistence\InMemoryUserRepository;

class InMemoryUserRepositoryTest extends AbstractUserRepositoryTest
{
    protected function getRepository(): UserRepository
    {
        return new InMemoryUserRepository();
    }
}
