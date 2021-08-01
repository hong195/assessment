<?php


namespace App\Infrastructure\Services;


use App\Domain\Model\User\Login;
use Illuminate\Auth\AuthManager;
use Tymon\JWTAuth\Providers\User\UserInterface;

class AuthenticationService implements JWTSubject, AuthenticatableContract
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

    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
    }

    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
    }
}
