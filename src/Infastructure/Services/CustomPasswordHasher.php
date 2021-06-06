<?php


namespace Infastructure\Services;

use \Domain\Model\User\PasswordHasher;

class CustomPasswordHasher implements PasswordHasher
{
    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}