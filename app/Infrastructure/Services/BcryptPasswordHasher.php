<?php


namespace App\Infrastructure\Services;


use Illuminate\Contracts\Hashing\Hasher;
use App\Domain\Model\User\PasswordHasher;

class BcryptPasswordHasher implements PasswordHasher
{
    private Hasher $hasher;

    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public function hash(string $password): string
    {
        return $this->hasher->make($password);
    }

    public function verify(string $password, string $hash): bool
    {
        return $this->hasher->check($password, $hash);
    }
}
