<?php


namespace App\Domain\Model\User;


interface PasswordHasher
{
    public function hash(string $password) : string;

    public function verify(string $password, string $hash) : bool;
}
