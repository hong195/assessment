<?php


namespace Domain\Model\User;


use Domain\Model\User\Exceptions\EmptyLoginException;
use Domain\Model\User\Exceptions\InvalidLoginException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
final class Login
{
    /**
     * @ORM\Column (type="string", name="login")
     */
    private string $login;

    /**
     * Login constructor.
     * @param string $login
     * @throws EmptyLoginException
     * @throws InvalidLoginException
     */
    public function __construct(string $login)
    {
        $this->assertIsNotEmpty($login);
        $this->assetValidLogin($login);
        $this->login = $login;
    }

    public function __toString(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @throws EmptyLoginException
     */
    private function assertIsNotEmpty(string $login)
    {
        if (empty($login)) {
            throw new EmptyLoginException;
        }
    }

    /**
     * @param string $login
     * @throws InvalidLoginException
     */
    public function assetValidLogin(string $login)
    {
        if (strlen($login) < 4) {
            throw new InvalidLoginException;
        }
        // todo proper validation with regexp
    }
}
