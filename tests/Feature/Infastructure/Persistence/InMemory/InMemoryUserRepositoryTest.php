<?php

namespace Tests\Feature\Infastructure\Persistence\InMemory;


use App\Domain\Model\User\UserRepository;
use App\Infrastructure\Persistence\InMemory\InMemoryUserRepository;
use Tests\Feature\Infastructure\Persistence\AbstractUserRepositoryTest;

class InMemoryUserRepositoryTest extends AbstractUserRepositoryTest
{
    protected function getRepository(): UserRepository
    {
        return new InMemoryUserRepository();
    }
}
