<?php


namespace Tests\Feature\Infastructure\Services;


use Domain\Model\User\PasswordHasher;
use Infastructure\Services\CustomPasswordHasher;
use Tests\TestCase;

class PasswordHashTest extends TestCase
{
    private PasswordHasher $passwordHasher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->passwordHasher = new CustomPasswordHasher();
    }

    public function test_can_set_hashed_password()
    {
        $aUserPass = 'user-pass';

        $hashedPassword = $this->passwordHasher->hash($aUserPass);

        $this->assertTrue($this->passwordHasher->verify($aUserPass, $hashedPassword));
    }
}
