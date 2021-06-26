<?php


namespace App\Infrastructure\Services;


use App\Domain\Model\User\Login;
use Illuminate\Auth\AuthManager;

class AuthenticationService
{
    private AuthManager $guard;

    private ?string $token = null;

    public function __construct()
    {
        $this->guard = auth();
    }

    public function signIn(Login $login, string $password)
    {
        $this->token = $this->guard->attempt([
            'login.login' => (string) $login,
            'password' => $password
        ]);
    }

    public function getUser(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return $this->guard->user();
    }

    public function getToken() : ?string
    {
        return $this->token;
    }
}
