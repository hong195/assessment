<?php


namespace Tests\Feature\Infastructure\Services;


use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Model\User\User;
use App\Domain\Model\User\UserRepository;
use App\Infrastructure\Services\AuthenticationService;
use Tests\Feature\DoctrineMigrationsTrait;
use Tests\TestCase;
use Tests\Builders\UserBuilder;

/**
 * @group integration
 */
class AuthenticationServiceTest extends TestCase
{
    use DoctrineMigrationsTrait;

    private UserRepository $repository;

    private EntityManagerInterface $em;

    private AuthenticationService $authenticationService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resetMigrations();

        $this->repository = app()->make(UserRepository::class);
        $this->em = app()->make(EntityManagerInterface::class);
        $this->authenticationService = new AuthenticationService();
    }

    public function test_user_can_sign_in_by_token()
    {
        $aUserPass = 'user-password';
        $aUser = UserBuilder::aUser()->withPassword($aUserPass)->build();
        $this->repository->add($aUser);
        $this->em->flush();

        $this->authenticationService->signIn($aUser->getLogin(), $aUserPass);
        /** @var User $foundUser */
        $foundUser = $this->authenticationService->getUser();
        $token = $this->authenticationService->getToken();

        $this->assertSame((string) $foundUser->getId(), (string) $aUser->getId());
        $this->assertNotEmpty($token);
    }
}
