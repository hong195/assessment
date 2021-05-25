<?php

namespace Tests\Feature\Infastructure\Persistence\Doctrine;


use Doctrine\ORM\EntityManagerInterface;
use Domain\Model\User\UserRepository;
use Infastructure\Persistence\Doctrine\DoctrineUserRepository;
use Tests\Feature\Infastructure\Persistence\AbstractUserRepositoryTest;

class DoctrineUserRepositoryTest extends AbstractUserRepositoryTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('doctrine:schema:drop --force --em="testing"');
        $this->artisan('doctrine:schema:create --em="testing"');
    }

    protected function getRepository(): UserRepository
    {
        $em = $this->app->make(EntityManagerInterface::class);
        return new DoctrineUserRepository($em);
    }
}
