<?php

namespace Tests\Feature\Infastructure\Persistence\InMemory;


use Domain\Model\User\UserRepository;
use Infastructure\Persistence\InMemory\InMemoryUserRepository;
use Tests\Feature\Infastructure\Persistence\AbstractUserRepositoryTest;

class InMemoryUserRepositoryTest extends AbstractUserRepositoryTest
{
    protected function getRepository(): UserRepository
    {
        return new InMemoryUserRepository();
    }
}
